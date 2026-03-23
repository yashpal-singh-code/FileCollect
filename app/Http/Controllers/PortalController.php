<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestEvent;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentUpload;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Notifications\DocumentRequestCompleted;
use Illuminate\Support\Facades\Storage;

class PortalController extends Controller
{
    public function access($token)
    {
        // Hash incoming token (DB stores hashed)
        $hashedToken = hash('sha256', trim($token));

        $documentRequest = DocumentRequest::with([
            'client:id,first_name,last_name,email,portal_enabled,status,portal_invite_token,portal_invited_at',
            'uploads:id,document_request_id,field_label,original_name,file_size,uploaded_at',
            'owner.plan'
        ])
            ->where('access_token', $hashedToken)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $client = $documentRequest->client;
        $owner  = $documentRequest->owner;
        $plan   = $owner?->plan;

        if ($client?->status === 'blocked') {
            abort(403, 'Your access has been blocked.');
        }

        if (!$documentRequest->viewed_at) {

            $updated = DocumentRequest::whereKey($documentRequest->id)
                ->whereNull('viewed_at')
                ->update([
                    'viewed_at'        => now(),
                    'status'           => DocumentRequest::STATUS_VIEWED,
                    'last_activity_at' => now(),
                ]);

            if ($updated) {
                DocumentRequestEvent::log(
                    $documentRequest,
                    'viewed',
                    [
                        'ip'    => request()->ip(),
                        'agent' => substr((string) request()->userAgent(), 0, 500),
                    ]
                );
            }
        }

        $maxUploadBytes   = $plan?->maxUploadBytes();
        $storageLimitBytes = $plan?->storageBytes();

        $storageUsed = DocumentRequest::where('owner_id', $owner->id)
            ->sum('total_upload_size');

        $storageRemaining = $storageLimitBytes !== null
            ? max(0, $storageLimitBytes - $storageUsed)
            : null;

        $storageRemainingMB = $storageRemaining !== null
            ? round($storageRemaining / 1024 / 1024)
            : null;

        $storageFull = $storageLimitBytes !== null
            ? $storageUsed >= $storageLimitBytes
            : false;

        $maxUploadMB = $maxUploadBytes !== null
            ? round($maxUploadBytes / 1024 / 1024)
            : null;

        $activationLink = null;

        if (
            $client?->status === 'inactive' &&
            $client?->portal_invite_token
        ) {
            $activationLink = route(
                'client.activate',
                $client->portal_invite_token
            );
        }

        $brandingEnabled = $plan?->hasFeature('branding') ?? false;

        $companyName = $brandingEnabled
            ? ($owner->company_name ?? 'Secure Document Portal')
            : 'Secure Document Portal';

        $companyLogo = $brandingEnabled
            ? ($owner->logo ?? null)
            : null;

        $total = $documentRequest->total_fields ?? 0;
        $uploadedCount = $documentRequest->upload_count ?? 0;
        $progress = $total > 0 ? round(($uploadedCount / $total) * 100) : 0;

        $loggedClient = auth('client')->user();

        $isCorrectClient = $loggedClient
            && $loggedClient->id === $documentRequest->client_id
            && $loggedClient->owner_id === $documentRequest->owner_id;

        return view('portal.access', compact(
            'documentRequest',
            'token',
            'activationLink',
            'storageRemainingMB',
            'storageFull',
            'maxUploadMB',
            'maxUploadBytes',
            'storageRemaining',
            'brandingEnabled',
            'companyName',
            'companyLogo',
            'total',
            'uploadedCount',
            'progress',
            'loggedClient',
            'isCorrectClient'
        ));
    }


    public function upload(Request $request, $token)
    {
        $hashed = hash('sha256', trim($token));

        $documentRequest = DocumentRequest::with(['owner.plan'])
            ->where('access_token', $hashed)
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | BASIC REQUEST VALIDATION
    |--------------------------------------------------------------------------
    */

        if ($documentRequest->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'This request has expired.'
            ], 403);
        }

        if (
            $documentRequest->status === DocumentRequest::STATUS_COMPLETED ||
            $documentRequest->status === DocumentRequest::STATUS_CANCELLED
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Uploads are not allowed for this request.'
            ], 403);
        }

        $owner = $documentRequest->owner;
        $plan  = $owner?->plan;

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'No active plan found.'
            ], 403);
        }

        /*
    |--------------------------------------------------------------------------
    | PLAN LIMITS
    |--------------------------------------------------------------------------
    */

        $maxUploadBytes    = $plan->maxUploadBytes();   // null = unlimited
        $storageLimitBytes = $plan->storageBytes();     // null = unlimited
        $allowedMimes      = $plan->allowedMimeTypes();

        $rules = ['required', 'file'];

        if ($maxUploadBytes !== null) {
            $rules[] = 'max:' . ceil($maxUploadBytes / 1024); // Laravel uses KB
        }

        if (!in_array('*', $allowedMimes)) {
            $rules[] = 'mimetypes:' . implode(',', $allowedMimes);
        }

        $validated = $request->validate([
            'file'        => $rules,
            'field_label' => ['required', 'string', 'max:255'],
        ]);

        $file     = $validated['file'];
        $fileSize = $file->getSize();

        try {

            return DB::transaction(function () use (
                $documentRequest,
                $file,
                $validated,
                $owner,
                $fileSize,
                $storageLimitBytes
            ) {

                $lockedOwner = User::whereKey($owner->id)
                    ->lockForUpdate()
                    ->first();

                /*
            |--------------------------------------------------------------------------
            | STORAGE LIMIT CHECK
            |--------------------------------------------------------------------------
            */

                $currentStorage = DocumentRequest::where('owner_id', $lockedOwner->id)
                    ->selectRaw('SUM(total_upload_size) as total')
                    ->lockForUpdate()
                    ->value('total') ?? 0;

                if (
                    $storageLimitBytes !== null &&
                    ($currentStorage + $fileSize) > $storageLimitBytes
                ) {
                    throw ValidationException::withMessages([
                        'file' => 'Storage quota exceeded.'
                    ]);
                }

                /*
            |--------------------------------------------------------------------------
            | SINGLE FILE PER FIELD
            |--------------------------------------------------------------------------
            */

                $exists = DocumentUpload::where('document_request_id', $documentRequest->id)
                    ->where('field_label', $validated['field_label'])
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'file' => 'This field already has a document uploaded.'
                    ]);
                }

                /*
            |--------------------------------------------------------------------------
            | DUPLICATE FILE DETECTION
            |--------------------------------------------------------------------------
            */

                $hash = hash_file('sha256', $file->getRealPath());

                $duplicate = DocumentUpload::where('document_request_id', $documentRequest->id)
                    ->where('file_hash', $hash)
                    ->exists();

                if ($duplicate) {
                    throw ValidationException::withMessages([
                        'file' => 'Duplicate file detected.'
                    ]);
                }

                /*
            |--------------------------------------------------------------------------
            | SAFE FILE NAME
            |--------------------------------------------------------------------------
            */

                $safeOriginal = Str::of($file->getClientOriginalName())
                    ->replaceMatches('/[^A-Za-z0-9.\-_]/', '_')
                    ->limit(120, '');

                $filename = Str::uuid() . '.' . $file->guessExtension();

                $disk = $documentRequest->disk ?? config('filesystems.default');

                $path = $file->storeAs(
                    "client_uploads/{$lockedOwner->uuid}/requests/{$documentRequest->uuid}",
                    $filename,
                    $disk
                );

                /*
            |--------------------------------------------------------------------------
            | SAVE UPLOAD
            |--------------------------------------------------------------------------
            */

                DocumentUpload::create([
                    'document_request_id' => $documentRequest->id,
                    'owner_id'            => $lockedOwner->id,
                    'uploaded_by'         => null,
                    'field_label'         => $validated['field_label'],
                    'file_path'           => $path,
                    'disk'                => $disk,
                    'original_name'       => $safeOriginal,
                    'mime_type'           => $file->getMimeType(),
                    'file_extension'      => $file->getClientOriginalExtension(),
                    'file_size'           => $fileSize,
                    'file_hash'           => $hash,
                    'uploaded_at'         => now(),
                ]);

                //     /*
                // |--------------------------------------------------------------------------
                // | NOTIFICATION
                // |--------------------------------------------------------------------------
                // */

                //     $lockedOwner->notify(
                //         new ClientUploadedDocument(
                //             $documentRequest,
                //             $validated['field_label']
                //         )
                //     );

                /*
            |--------------------------------------------------------------------------
            | UPDATE PROGRESS
            |--------------------------------------------------------------------------
            */

                $documentRequest->refresh();

                $isCompleted =
                    $documentRequest->upload_count >= $documentRequest->total_fields;

                $documentRequest->update([
                    'status' => $isCompleted
                        ? DocumentRequest::STATUS_COMPLETED
                        : DocumentRequest::STATUS_IN_PROGRESS,
                    'completed_at'     => $isCompleted ? now() : null,
                    'last_activity_at' => now(),
                ]);

                if ($isCompleted) {

                    DocumentRequestEvent::log(
                        $documentRequest,
                        'completed'
                    );

                    $lockedOwner->notify(
                        new DocumentRequestCompleted($documentRequest)
                    );
                }

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully.'
                ]);
            });
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->errors()['file'][0]
            ], 422);
        } catch (\Throwable $e) {

            Log::error('UPLOAD_FAILURE', [
                'error'      => $e->getMessage(),
                'owner_id'   => $owner->id ?? null,
                'request_id' => $documentRequest->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed. Please try again.'
            ], 500);
        }
    }

    public function complete($token)
    {
        $hashed = hash('sha256', trim($token));

        $documentRequest = DocumentRequest::with('owner')
            ->where('access_token', $hashed)
            ->firstOrFail();

        if ($documentRequest->isExpired()) {
            return back()->with('error', 'This request has expired.');
        }

        if ($documentRequest->isCompleted()) {
            return back()->with('error', 'This request is already completed.');
        }

        if ($documentRequest->status === DocumentRequest::STATUS_CANCELLED) {
            return back()->with('error', 'This request has been cancelled.');
        }

        // Optional: prevent manual completion if nothing uploaded
        if ($documentRequest->upload_count <= 0) {
            return back()->with('error', 'You must upload at least one document before completing.');
        }

        try {

            DB::transaction(function () use ($documentRequest) {

                // Lock row to prevent race condition
                $locked = DocumentRequest::whereKey($documentRequest->id)
                    ->lockForUpdate()
                    ->first();

                if ($locked->status === DocumentRequest::STATUS_COMPLETED) {
                    return;
                }

                $locked->update([
                    'status'           => DocumentRequest::STATUS_COMPLETED,
                    'completed_at'     => now(),
                    'last_activity_at' => now(),
                ]);

                DocumentRequestEvent::log(
                    $locked,
                    'completed_manual'
                );
            });
        } catch (\Throwable $e) {

            Log::error('MANUAL_COMPLETE_FAILED', [
                'error'      => $e->getMessage(),
                'request_id' => $documentRequest->id,
            ]);

            return back()->with('error', 'Something went wrong.');
        }

        return back()->with('success', 'Request completed successfully.');
    }

    public function deleteUpload($id)
    {
        $upload = DocumentUpload::findOrFail($id);

        try {

            DB::transaction(function () use ($upload) {

                $documentRequest = DocumentRequest::lockForUpdate()
                    ->findOrFail($upload->document_request_id);

                Storage::disk($upload->disk)->delete($upload->file_path);

                $upload->delete();

                $documentRequest->refresh();

                if ($documentRequest->status === DocumentRequest::STATUS_COMPLETED) {

                    $documentRequest->update([
                        'status' => DocumentRequest::STATUS_IN_PROGRESS,
                        'completed_at' => null,
                        'last_activity_at' => now(),
                    ]);
                }
            });

            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Delete failed.'
            ], 500);
        }
    }
}

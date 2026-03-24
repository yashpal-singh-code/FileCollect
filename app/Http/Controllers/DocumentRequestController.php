<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DocumentRequest;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\DocumentRequestMail;
use App\Models\DocumentUpload;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Resolve owner (for team users)
        $owner = $authUser->getAccountOwner();
        $ownerId = $owner->id;

        $search = trim((string) $request->query('search', ''));

        /*
    |--------------------------------------------------------------------------
    | BASE QUERY (OWNER SCOPED)
    |--------------------------------------------------------------------------
    */

        $baseQuery = DocumentRequest::where('owner_id', $ownerId);

        /*
    |--------------------------------------------------------------------------
    | SEARCH (REQUEST + CLIENT)
    |--------------------------------------------------------------------------
    */

        if (!empty($search)) {

            $baseQuery->where(function ($query) use ($search) {

                // Search request number
                $query->where('request_number', 'like', "%{$search}%")

                    // Search client details
                    ->orWhereHas('client', function ($q) use ($search) {

                        $searchLower = strtolower($search);

                        $q->whereRaw("LOWER(first_name) LIKE ?", ["%{$searchLower}%"])
                            ->orWhereRaw("LOWER(last_name) LIKE ?", ["%{$searchLower}%"])
                            ->orWhereRaw("LOWER(email) LIKE ?", ["%{$searchLower}%"])

                            // Full name search (John Doe)
                            ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ["%{$searchLower}%"]);
                    });
            });
        }

        /*
|--------------------------------------------------------------------------
| STATUS FILTER
|--------------------------------------------------------------------------
*/

        if ($request->filled('status')) {

            switch ($request->status) {

                // Pending = NOT completed (includes overdue)
                case 'pending':
                    $baseQuery->where('status', '!=', DocumentRequest::STATUS_COMPLETED);
                    break;

                // Overdue = NOT completed + expired
                case 'overdue':
                    $baseQuery->where('status', '!=', DocumentRequest::STATUS_COMPLETED)
                        ->whereNotNull('expires_at')
                        ->where('expires_at', '<', now());
                    break;

                // Completed
                case 'completed':
                    $baseQuery->where('status', DocumentRequest::STATUS_COMPLETED);
                    break;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | COUNTS (CLONE QUERY)
    |--------------------------------------------------------------------------
    */

        // Total
        $totalRequests = (clone $baseQuery)->count();

        // Pending
        $pendingRequests = (clone $baseQuery)
            ->where('status', '!=', DocumentRequest::STATUS_COMPLETED)
            ->count();

        // Completed
        $completedRequests = (clone $baseQuery)
            ->where('status', DocumentRequest::STATUS_COMPLETED)
            ->count();

        /*
    |--------------------------------------------------------------------------
    | PLAN LIMIT
    |--------------------------------------------------------------------------
    */

        $planLimit = $owner->currentPlan()?->document_requests;

        // Used requests this month
        $usedRequests = (clone $baseQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Remaining
        $remainingRequests = is_null($planLimit)
            ? null
            : max($planLimit - $usedRequests, 0);

        // Limit reached
        $limitReached = !is_null($planLimit)
            && $usedRequests >= $planLimit;

        // Usage %
        $usagePercent = !is_null($planLimit) && $planLimit > 0
            ? min(100, round(($usedRequests / $planLimit) * 100))
            : 0;

        /*
    |--------------------------------------------------------------------------
    | FINAL LIST
    |--------------------------------------------------------------------------
    */

        $documentRequests = $baseQuery
            ->with(['client:id,uuid,first_name,last_name,email'])
            ->latest()
            ->paginate(50)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

        return view('document_requests.index', compact(
            'documentRequests',
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'planLimit',
            'usedRequests',
            'remainingRequests',
            'limitReached',
            'usagePercent'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        $owner = $authUser->getAccountOwner();
        $plan = $owner->currentPlan();

        // Feature check
        if (!$plan?->client_portal) {
            abort(403, 'Your plan does not allow document requests.');
        }

        // Limit check
        if (!$owner->canUse('document_requests')) {
            return redirect()
                ->route('document-requests.index')
                ->with('error', 'You have reached your document request limit.');
        }

        // Selected client (UUID based)
        $selectedClient = null;

        if ($request->filled('client')) {
            $selectedClient = Client::where('uuid', $request->client)
                ->where('owner_id', $owner->id) // FIXED
                ->where('status', 'active')
                ->firstOrFail();
        }

        // Client list (tenant scoped)
        $clients = Client::where('owner_id', $owner->id) // FIXED
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get(['id', 'uuid', 'first_name', 'last_name', 'email']);

        $clientJson = $clients->map(fn($c) => [
            'id' => $c->id,
            'uuid' => $c->uuid,
            'name' => trim($c->first_name . ' ' . $c->last_name),
            'email' => $c->email,
        ])->values();

        // Template list (tenant scoped)
        $templates = Template::where('owner_id', $owner->id) // FIXED
            ->latest()
            ->get(['uuid', 'name', 'fields']);

        $templateJson = $templates->map(fn($t) => [
            'uuid' => $t->uuid,
            'name' => $t->name,
            'fields' => $t->fields,
        ])->values();

        return view('document_requests.create', [
            'clients' => $clients,
            'templates' => $templates,
            'selectedClient' => $selectedClient,
            'templateJson' => $templateJson,
            'clientJson' => $clientJson,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();
        $plan     = $owner->currentPlan();
        $now      = now();

        if (!$plan || !$plan->client_portal) {
            return redirect()->route('pricing')
                ->with('error', 'Upgrade your plan to use document requests.');
        }

        if (!$owner->canUse('document_requests')) {
            return redirect()->route('document-requests.index')
                ->with('error', 'You have reached your document request limit.');
        }

        $validated = $request->validate([
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')
                    ->where('owner_id', $owner->id),
            ],
            'template_uuid' => [
                'required',
                Rule::exists('templates', 'uuid')
                    ->where('owner_id', $owner->id),
            ],
            'message'    => 'nullable|string|max:2000',
            'expires_at' => 'nullable|date|after:today',
        ]);

        do {
            $plainToken  = Str::random(64);
            $hashedToken = hash('sha256', $plainToken);
        } while (
            DocumentRequest::where('access_token', $hashedToken)->exists()
        );

        $documentRequest = DB::transaction(function () use (
            $validated,
            $authUser,
            $owner,
            $hashedToken,
            $plainToken,
            $now
        ) {

            $template = Template::where('uuid', $validated['template_uuid'])
                ->where('owner_id', $owner->id)
                ->firstOrFail();

            do {
                $requestNumber = 'REQ-' . now()->format('YmdHisv');
            } while (
                DocumentRequest::where('owner_id', $owner->id)
                ->where('request_number', $requestNumber)
                ->exists()
            );

            $portalUrl = route('portal.access', $plainToken);

            $documentRequest = DocumentRequest::create([
                'uuid'              => (string) Str::orderedUuid(),
                'request_number'    => $requestNumber,
                'owner_id'          => $owner->id,
                'requested_by'      => $authUser->id,
                'client_id'         => $validated['client_id'],
                'template_uuid'     => $template->uuid,
                'access_token'      => $hashedToken,
                'portal_url'        => $portalUrl,
                'fields'            => $template->fields,
                'total_fields'      => is_array($template->fields) ? count($template->fields) : 0,
                'upload_count'      => 0,
                'total_upload_size' => 0,
                'message'           => $validated['message'] ?? null,
                'expires_at' => $validated['expires_at'] ?: now()->addDays(15),
                'status'            => DocumentRequest::STATUS_DRAFT,
                'last_activity_at'  => $now,
            ]);

            // increment template usage
            $template->increment('usage_count');

            return $documentRequest;
        });

        return redirect()
            ->route('document-requests.index')
            ->with([
                'success'     => 'Document request created successfully.',
                'portal_link' => $documentRequest->portal_url,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentRequest $documentRequest)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();

        // Tenant isolation (critical)
        abort_if($documentRequest->owner_id !== $owner->id, 404);

        $documentRequest->load([
            'client',
            'requester',
            'uploads'
        ]);

        // Fetch all requests for this client under same tenant
        $clientRequests = $documentRequest->client
            ->documentRequests()
            ->where('owner_id', $owner->id)
            ->with('uploads')
            ->latest()
            ->get();

        return view('document_requests.show', [
            'documentRequest' => $documentRequest,
            'requests'        => $clientRequests
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentRequest $documentRequest)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();

        // Tenant isolation (security critical)
        abort_unless(
            $documentRequest->owner_id === $owner->id,
            404
        );

        $documentRequest->load('client');

        $templates = Template::query()
            ->where('owner_id', $owner->id)
            ->latest()
            ->get([
                'uuid',
                'name',
                'fields'
            ]);

        $templateJson = $templates->map(fn($t) => [
            'uuid'   => $t->uuid,
            'name'   => $t->name,
            'fields' => $t->fields,
        ])->values();

        return view('document_requests.edit', [
            'documentRequest' => $documentRequest,
            'templates'       => $templates,
            'templateJson'    => $templateJson,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentRequest $documentRequest)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();

        // Tenant protection (critical isolation)
        abort_unless(
            $documentRequest->owner_id === $owner->id,
            404
        );

        // Validation (tenant scoped)
        $validated = $request->validate([

            'template_uuid' => [
                'required',
                Rule::exists('templates', 'uuid')
                    ->where('owner_id', $owner->id)
            ],

            'message'    => 'nullable|string|max:2000',
            'expires_at' => 'nullable|date|after:today'
        ]);

        try {

            DB::transaction(function () use ($validated, $documentRequest, $owner) {

                // Detect template change
                if ($validated['template_uuid'] !== $documentRequest->template_uuid) {

                    // Prevent template change after sending
                    if ($documentRequest->status !== 'draft') {
                        abort(422, 'Template cannot be changed after sending.');
                    }

                    $template = Template::where('uuid', $validated['template_uuid'])
                        ->where('owner_id', $owner->id)
                        ->firstOrFail();

                    // Refresh template snapshot
                    $documentRequest->update([
                        'template_uuid' => $template->uuid,
                        'fields'        => $template->fields,
                        'total_fields'  => is_array($template->fields)
                            ? count($template->fields)
                            : 0,
                    ]);

                    // Optional usage increment
                    if (method_exists($template, 'incrementUsage')) {
                        $template->incrementUsage();
                    }
                }

                // Update editable fields
                $documentRequest->update([
                    'message'    => $validated['message'] ?? null,
                    'expires_at' => $validated['expires_at'] ?? null,
                ]);
            });

            return redirect()
                ->route('document-requests.index')
                ->with('success', 'Document request updated successfully.');
        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentRequest $documentRequest)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();

        abort_unless(
            $documentRequest->owner_id === $owner->id,
            404
        );

        // OLD
        // if ($documentRequest->status === 'completed')

        // NEW
        if ($documentRequest->status === DocumentRequest::STATUS_COMPLETED) {
            return back()->with(
                'error',
                'Completed document requests cannot be deleted.'
            );
        }

        $documentRequest->delete();

        return back()->with('success', 'Request deleted successfully.');
    }


    public function send(DocumentRequest $documentRequest)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();

        abort_unless($documentRequest->owner_id === $owner->id, 404);

        if ($documentRequest->status !== DocumentRequest::STATUS_DRAFT) {
            return back()->with('error', 'Request already sent.');
        }

        $client = $documentRequest->client;

        if (!$client || !$client->email) {
            return back()->with('error', 'Client email address is missing.');
        }

        Mail::to($client->email)
            ->send(new DocumentRequestMail(
                $documentRequest,
                $documentRequest->portal_url
            ));

        $documentRequest->update([
            // OLD
            // 'status' => 'sent',

            // NEW
            'status'           => DocumentRequest::STATUS_SENT,
            'sent_at'          => now(),
            'expires_at'       => $documentRequest->expires_at ?? now()->addDays(7),
            'last_activity_at' => now(),
        ]);

        return back()->with('success', 'Request email sent successfully.');
    }

    public function resend(DocumentRequest $documentRequest)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();

        // Tenant protection
        abort_unless($documentRequest->owner_id === $owner->id, 404);

        if (!$documentRequest->sent_at) {
            return back()->with('error', 'This request has not been sent yet.');
        }

        $client = $documentRequest->client;

        if (!$client || !$client->email) {
            return back()->with('error', 'Client email address is missing.');
        }

        if (!$documentRequest->portal_url) {
            return back()->with('error', 'Portal link is missing.');
        }

        try {

            Mail::to($client->email)
                ->send(new DocumentRequestMail(
                    $documentRequest,
                    $documentRequest->portal_url
                ));

            $documentRequest->update([
                'last_activity_at' => now(),
            ]);

            if (class_exists(\App\Models\DocumentRequestEvent::class)) {
                \App\Models\DocumentRequestEvent::create([
                    'document_request_id' => $documentRequest->id,
                    'owner_id'            => $documentRequest->owner_id,
                    'event'               => 'resent',
                    'meta'                => [],
                    'created_at'          => now(),
                ]);
            }
        } catch (\Throwable $e) {

            report($e);

            return back()->with('error', 'Failed to resend email.');
        }

        return back()->with('success', 'Request email resent successfully.');
    }

    // AJAX Endpoint to Generate Link (Secure + Owner Only)
    public function generateLink(DocumentRequest $documentRequest)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        $owner    = $authUser->getAccountOwner();
        // Tenant protection (hide existence)
        abort_unless(
            $documentRequest->owner_id === $owner->id,
            404
        );

        // Ensure portal link exists
        if (!$documentRequest->portal_url) {
            return response()->json([
                'error' => 'Portal link is not available.'
            ], 422);
        }

        return response()->json([
            'link' => $documentRequest->portal_url,
        ]);
    }


    public function download(DocumentUpload $upload)
    {
        // 🔒 Tenant security
        abort_unless($upload->owner_id === Auth::id(), 403);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk($upload->disk ?? 'private');

        // Ensure file exists
        abort_unless($disk->exists($upload->file_path), 404);

        $request = $upload->documentRequest;

        // File label
        $label = $upload->field_label
            ?? pathinfo($upload->original_name, PATHINFO_FILENAME)
            ?? 'document';

        // Extension
        $extension = $upload->file_extension
            ?? pathinfo($upload->original_name, PATHINFO_EXTENSION)
            ?? 'file';

        // Client name
        $clientName = optional($request?->client)->full_name ?? 'client';

        // Clean filename
        $fileName =
            Str::slug($clientName)
            . '-'
            . ($request?->request_number ?? 'request')
            . '-'
            . Str::slug($label)
            . '.'
            . $extension;

        // 🔥 Generate secure temporary URL (FORCE DOWNLOAD)
        $url = $disk->temporaryUrl(
            $upload->file_path,
            now()->addMinutes(5),
            [
                'ResponseContentDisposition' => 'attachment; filename="' . $fileName . '"'
            ]
        );

        // 🔒 Redirect to S3 secure URL
        return redirect($url);
    }

    public function downloadAll($id)
    {
        $request = DocumentRequest::where('id', $id)
            ->where('owner_id', Auth::id())
            ->with(['uploads', 'client'])
            ->firstOrFail();

        if ($request->uploads->isEmpty()) {
            return back()->withErrors('No files available for this request.');
        }

        $clientName = optional($request->client)->full_name ?? 'client';

        $zipName = Str::slug($clientName)
            . '-' . $request->request_number
            . '.zip';

        $tempDir = storage_path('app/temp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir . '/' . $zipName;

        $zip = new \ZipArchive;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create ZIP file.');
        }

        foreach ($request->uploads as $upload) {

            $disk = Storage::disk($upload->disk ?? 'private');

            if (!$disk->exists($upload->file_path)) {
                continue;
            }

            $label = $upload->field_label
                ?? pathinfo($upload->original_name, PATHINFO_FILENAME)
                ?? 'document';

            $extension = $upload->file_extension
                ?? pathinfo($upload->original_name, PATHINFO_EXTENSION)
                ?? 'file';

            $fileName =
                Str::slug($clientName)
                . '-'
                . $request->request_number
                . '-'
                . Str::slug($label)
                . '.'
                . $extension;

            // Prevent duplicate names
            $finalName = $fileName;
            $counter = 1;

            while ($zip->locateName($finalName) !== false) {
                $finalName =
                    pathinfo($fileName, PATHINFO_FILENAME)
                    . "($counter)."
                    . $extension;

                $counter++;
            }

            // 🔥 STREAM FROM S3 (no memory issue)
            $stream = $disk->readStream($upload->file_path);

            if ($stream) {
                $zip->addFromString($finalName, stream_get_contents($stream));
                fclose($stream);
            }
        }

        $zip->close();

        return response()
            ->download($zipPath, $zipName)
            ->deleteFileAfterSend(true);
    }
}

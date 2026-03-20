<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Support;
use App\Models\DocumentUpload;
use App\Models\DocumentRequest;
use App\Models\DocumentRequestEvent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $ownerId = $owner->id;

        // Get active plan (Stripe or fallback)
        $plan = $owner->currentPlan();


        /*
    |--------------------------------------------------------------------------
    | PLAN LIMITS
    |--------------------------------------------------------------------------
    */

        $requestLimit  = $plan->requests_limit ?? 0;
        $clientLimit   = $plan->clients_limit ?? 0;
        $documentLimit = $plan->documents_limit ?? 0;

        // Convert MB → Bytes if needed
        $storageLimit = isset($plan->storage_mb)
            ? $plan->storage_mb * 1024 * 1024
            : (10 * 1024 * 1024 * 1024); // fallback 10GB


        /*
    |--------------------------------------------------------------------------
    | STORAGE (OWNER LEVEL)
    |--------------------------------------------------------------------------
    */

        $storageUsed = DocumentUpload::where('owner_id', $ownerId)
            ->sum('file_size');

        $storageRemaining = max($storageLimit - $storageUsed, 0);

        $storageUsagePercent = $storageLimit > 0
            ? round(($storageUsed / $storageLimit) * 100)
            : 0;


        /*
    |--------------------------------------------------------------------------
    | COUNTS (OWNER LEVEL)
    |--------------------------------------------------------------------------
    */

        $baseRequestQuery = DocumentRequest::where('owner_id', $ownerId);

        $totalRequests = (clone $baseRequestQuery)->count();

        $pendingRequests = (clone $baseRequestQuery)
            ->where('status', '!=', 'completed')
            ->count();

        $completedRequests = (clone $baseRequestQuery)
            ->where('status', 'completed')
            ->count();

        $overdueRequests = (clone $baseRequestQuery)
            ->where('status', '!=', 'completed')
            ->whereDate('expires_at', '<', now())
            ->count();

        $clients = Client::where('owner_id', $ownerId)->count();

        $documents = DocumentUpload::where('owner_id', $ownerId)->count();

        // $openTickets = Support::where('owner_id', $ownerId)
        //     ->where('status', 'open')
        //     ->count();


        /*
    |--------------------------------------------------------------------------
    | RECENT DATA
    |--------------------------------------------------------------------------
    */

        $recentRequests = DocumentRequest::where('owner_id', $ownerId)
            ->with('client')
            ->latest()
            ->limit(10)
            ->get();

        $recentDocuments = DocumentUpload::where('owner_id', $ownerId)
            ->with('client')
            ->latest()
            ->limit(5)
            ->get();

        $activities = DocumentRequestEvent::where('owner_id', $ownerId)
            ->latest()
            ->limit(6)
            ->get();


        /*
    |--------------------------------------------------------------------------
    | REQUEST STATUS %
    |--------------------------------------------------------------------------
    */

        $total = $totalRequests > 0 ? $totalRequests : 1;

        $completedPercent = round(($completedRequests / $total) * 100);
        $pendingPercent   = round(($pendingRequests / $total) * 100);
        $overduePercent   = round(($overdueRequests / $total) * 100);


        /*
    |--------------------------------------------------------------------------
    | UPLOAD TREND (LAST 7 DAYS)
    |--------------------------------------------------------------------------
    */

        $uploads = collect();

        for ($i = 6; $i >= 0; $i--) {

            $date = now()->subDays($i)->format('Y-m-d');

            $count = DocumentUpload::where('owner_id', $ownerId)
                ->whereDate('created_at', $date)
                ->count();

            $uploads->put($date, $count);
        }


        /*
    |--------------------------------------------------------------------------
    | CLIENT ACTIVITY
    |--------------------------------------------------------------------------
    */

        $clientActivity = DocumentUpload::where('owner_id', $ownerId)
            ->selectRaw('uploaded_by, COUNT(*) as total')
            ->groupBy('uploaded_by')
            ->pluck('total', 'uploaded_by');


        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

        return view('dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'overdueRequests',
            'clients',
            'documents',
            'recentRequests',
            'recentDocuments',
            'activities',
            'completedPercent',
            'pendingPercent',
            'overduePercent',
            'uploads',
            'clientActivity',
            'storageUsed',
            'storageLimit',
            'requestLimit',
            'clientLimit',
            'documentLimit',
            'storageRemaining',
            'storageUsagePercent',
        ));
    }
}
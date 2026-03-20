<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX (Tenant + Plan Aware)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        // Base query for clients belonging to the account owner
        $clientsQuery = Client::where('owner_id', $owner->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = trim($request->search);

            $clientsQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $clientsQuery->where('status', $request->status);
        }

        $clients = $clientsQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Stats for dashboard widgets
        $baseQuery = Client::where('owner_id', $owner->id);

        $totalClients    = (clone $baseQuery)->count();
        $activeClients   = (clone $baseQuery)->where('status', 'active')->count();
        $inactiveClients = (clone $baseQuery)->where('status', 'inactive')->count();
        $blockedClients  = (clone $baseQuery)->where('status', 'blocked')->count();

        //        $planLimit = null;
        $planLimit = $owner->currentPlan()?->clients;

        $usedClients = $owner->getUsageCount('clients');

        $remainingClients = is_null($planLimit)
            ? null
            : max($planLimit - $usedClients, 0);

        $limitReached = !is_null($planLimit)
            && $usedClients >= $planLimit;

        $usagePercent = !is_null($planLimit) && $planLimit > 0
            ? min(100, round(($usedClients / $planLimit) * 100))
            : 0;

        return view('clients.index', compact(
            'clients',
            'totalClients',
            'activeClients',
            'inactiveClients',
            'blockedClients',
            'planLimit',
            'usedClients',
            'remainingClients',
            'limitReached',
            'usagePercent'
        ));
    }


    // create method with plan limit check
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $owner = $user->getAccountOwner();

        if (!$owner->canUse('clients')) {
            abort(403, 'Client limit reached for your plan.');
        }

        return view('clients.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (HARD PLAN CHECK)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        // 🔐 HARD SECURITY CHECK
        if (!$owner->canUse('clients')) {
            abort(403, 'Client limit reached for your plan.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],

            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('clients')
                    ->where(fn($q) => $q->where('owner_id', $owner->id))
            ],

            'phone'        => ['nullable', 'string', 'max:25'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'status'       => ['nullable', 'in:active,inactive,blocked'],

            'address_line_1' => ['nullable', 'string', 'max:150'],
            'address_line_2' => ['nullable', 'string', 'max:150'],
            'city'           => ['nullable', 'string', 'max:100'],
            'state'          => ['nullable', 'string', 'max:100'],
            'postal_code'    => ['nullable', 'string', 'max:20'],
            'country'        => ['nullable', 'string', 'max:80'],
            'notes'          => ['nullable', 'string'],
            'is_active'          => ['nullable', 'boolean'],
        ]);

        $validated['owner_id'] = $owner->id;
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['portal_enabled'] = true;

        $rawToken = Str::random(64);

        $validated['portal_invite_token'] = hash('sha256', $rawToken);
        $validated['portal_invited_at'] = now();

        Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client created successfully.');
    }



    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Client $client)
    {
        $this->authorizeOwnerAccess($client);

        $documentRequests = DocumentRequest::where('client_id', $client->id)
            ->with('uploads')
            ->latest()
            ->paginate(10);

        return view('clients.show', compact('client', 'documentRequests'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Client $client)
    {
        $this->authorizeOwnerAccess($client);

        return view('clients.edit', compact('client'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Client $client)
    {
        $this->authorizeOwnerAccess($client);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $owner = $user->getAccountOwner();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('clients')
                    ->where(fn($q) => $q->where('owner_id', $owner->id))
                    ->ignore($client->id)
            ],
            'phone' => ['nullable', 'string', 'max:25'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:active,inactive,blocked'],
            'address_line_1' => ['nullable', 'string', 'max:150'],
            'address_line_2' => ['nullable', 'string', 'max:150'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:80'],
            'notes' => ['nullable', 'string'],
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Client $client)
    {
        $this->authorizeOwnerAccess($client);

        $client->delete();

        return back()->with('success', 'Client deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | BULK DELETE (Tenant Secure)
    |--------------------------------------------------------------------------
    */
    public function bulkDelete(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $owner = $authUser->getAccountOwner();

        $ids = $request->input('selected_clients', []);

        if (!empty($ids)) {

            Client::whereIn('id', $ids)
                ->where('owner_id', $owner->id)
                ->delete();
        }

        return back()->with('success', 'Selected clients deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT SECURITY
    |--------------------------------------------------------------------------
    */
    private function authorizeOwnerAccess(Client $client): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $owner = $user->getAccountOwner();

        if ($client->owner_id !== $owner->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}

<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerUserController extends Controller
{

    /**
     * Display a listing of tenant admins.
     */
    public function index(Request $request)
    {
        $query = User::role('super_admin')->with('plan');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Status filter
        if ($request->status === 'active') {
            $query->where('is_active', 1);
        }

        if ($request->status === 'inactive') {
            $query->where('is_active', 0);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('owner.users.index', compact('users'));
    }


    /**
     * Show create form
     */
    public function create()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('owner.users.create', compact('plans'));
    }


    /**
     * Store tenant
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        $data['password'] = Hash::make($request->password);

        $data['is_active'] = $request->boolean('is_active');

        $user = User::create($data);

        // Assign role
        $user->assignRole('super_admin');

        return redirect()
            ->route('owner.users.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Show tenant
     */
    public function show(string $id)
    {
        $user = User::with([
            'companySetting',
            'plan',
            'subscriptions'
        ])->findOrFail($id);

        // Get subscription
        $subscription = $user->subscription('default');

        // Default plan (free plan from users table)
        $activePlan = $user->plan;

        // Billing cycle
        $activeBilling = $user->billing_cycle ?? null;

        // Next billing timestamp
        $currentPeriodEnd = null;

        if ($subscription) {

            // Stripe subscription
            $stripeSub = $subscription->asStripeSubscription();

            if ($stripeSub) {
                $currentPeriodEnd = $stripeSub->current_period_end ?? null;
            }

            // Detect plan using Stripe price
            $priceId = $subscription->stripe_price;

            $paidPlan = \App\Models\Plan::where('stripe_price_monthly', $priceId)
                ->orWhere('stripe_price_yearly', $priceId)
                ->first();

            if ($paidPlan) {
                $activePlan = $paidPlan;
            }
        }

        // Total team members under this tenant
        $totalUsers = $user->teamMembers()->count();

        // Total document requests
        $totalDocuments = $user->documentRequests()->count();

        return view('owner.users.show', [
            'user' => $user,
            'subscription' => $subscription,
            'activePlan' => $activePlan,
            'activeBilling' => $activeBilling,
            'currentPeriodEnd' => $currentPeriodEnd,
            'totalUsers' => $totalUsers,
            'totalDocuments' => $totalDocuments,
        ]);
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $plans = Plan::orderBy('name')->get();

        return view('owner.users.edit', compact('user', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'plan_id' => 'nullable',
            'billing_cycle' => 'nullable',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()
            ->route('owner.users.index')
            ->with('success', 'Tenant updated successfully');
    }

    // /**
    //  * Delete tenant
    //  */
    // public function destroy(string $id)
    // {
    //     $user = User::findOrFail($id);

    //     $user->delete();

    //     return redirect()
    //         ->route('owner.users.index')
    //         ->with('success', 'Tenant deleted successfully.');
    // }


    // /**
    //  * Bulk delete tenants
    //  */
    // public function bulkDelete(Request $request)
    // {

    //     $ids = $request->selected_users;

    //     if (!$ids) {
    //         return back()->with('error', 'No users selected.');
    //     }

    //     User::whereIn('id', $ids)->delete();

    //     return redirect()
    //         ->route('owner.users.index')
    //         ->with('success', 'Selected tenants deleted successfully.');
    // }
}

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

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

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

        $user->assignRole('super_admin');

        return redirect()
            ->route('owner.users.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Show tenant (RAZORPAY VERSION)
     */
    public function show(string $id)
    {
        $user = User::with([
            'companySetting',
            'plan'
        ])->findOrFail($id);

        // ✅ Default plan (free/manual)
        $activePlan = $user->plan;
        $activeBilling = $user->billing_cycle ?? null;
        $currentPeriodEnd = $user->current_period_end ?? null;

        // ✅ Razorpay subscription check
        $isSubscribed = !empty($user->razorpay_subscription_id);

        if ($isSubscribed) {

            // 🔍 Match plan using Razorpay plan ID
            $paidPlan = Plan::where('razorpay_plan_monthly', $user->razorpay_plan_id)
                ->orWhere('razorpay_plan_yearly', $user->razorpay_plan_id)
                ->first();

            if ($paidPlan) {
                $activePlan = $paidPlan;
            }

            // 🔄 Detect billing cycle
            if ($activePlan) {
                if ($activePlan->razorpay_plan_monthly === $user->razorpay_plan_id) {
                    $activeBilling = 'monthly';
                } elseif ($activePlan->razorpay_plan_yearly === $user->razorpay_plan_id) {
                    $activeBilling = 'yearly';
                }
            }
        }

        // 📊 Stats
        $totalUsers = $user->teamMembers()->count();
        $totalDocuments = $user->documentRequests()->count();

        return view('owner.users.show', [
            'user' => $user,
            'isSubscribed' => $isSubscribed,
            'activePlan' => $activePlan,
            'activeBilling' => $activeBilling,
            'currentPeriodEnd' => $currentPeriodEnd,
            'totalUsers' => $totalUsers,
            'totalDocuments' => $totalDocuments,
        ]);
    }

    /**
     * Edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $plans = Plan::orderBy('name')->get();

        return view('owner.users.edit', compact('user', 'plans'));
    }

    /**
     * Update user
     */
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
}

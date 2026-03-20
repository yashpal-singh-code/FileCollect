<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;

class OwnerSubscriptionController extends Controller
{
    /**
     * Display tenant subscriptions
     */
    public function index()
    {
        $users = User::with(['plan', 'companySetting'])
            ->role('super_admin')
            ->latest()
            ->paginate(15);

        return view('owner.subscriptions.index', compact('users'));
    }

    /**
     * Show subscription details
     */
    public function show($id)
    {
        $user = User::with(['plan', 'companySetting'])
            ->findOrFail($id);

        $subscription = $user->subscription('default');

        return view('owner.subscriptions.show', [
            'user' => $user,
            'subscription' => $subscription
        ]);
    }

    /**
     * Cancel tenant subscription
     */
    public function cancel($id)
    {
        $user = User::findOrFail($id);

        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
        }

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Resume subscription
     */
    public function resume($id)
    {
        $user = User::findOrFail($id);

        if ($user->subscription('default')->onGracePeriod()) {
            $user->subscription('default')->resume();
        }

        return back()->with('success', 'Subscription resumed.');
    }
}

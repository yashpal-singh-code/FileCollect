<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Models\Support;
use App\Models\Notification;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $activeUsers = User::where('is_active', true)->count();

        $owners = User::where('is_owner', true)->count();

        $plans = Plan::count();

        $supportTickets = Support::count();

        $notifications = Notification::count();

        $recentUsers = User::latest()->take(5)->get();

        return view('owner.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'owners',
            'plans',
            'supportTickets',
            'notifications',
            'recentUsers'
        ));
    }
}

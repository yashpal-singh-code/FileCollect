@extends('owner.layouts.app')

@section('title', 'Owner Dashboard')

@section('owner_content')

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                Platform Dashboard
            </h1>

            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Overview of FileCollect SaaS platform
            </p>
        </div>

        <!-- Stats -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-5 rounded-xl">
                <div class="text-xs text-neutral-500">Total Users</div>
                <div class="text-2xl font-semibold mt-1">{{ $totalUsers }}</div>
            </div>

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-5 rounded-xl">
                <div class="text-xs text-neutral-500">Active Users</div>
                <div class="text-2xl font-semibold mt-1">{{ $activeUsers }}</div>
            </div>

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-5 rounded-xl">
                <div class="text-xs text-neutral-500">Platform Owners</div>
                <div class="text-2xl font-semibold mt-1">{{ $owners }}</div>
            </div>

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-5 rounded-xl">
                <div class="text-xs text-neutral-500">Plans</div>
                <div class="text-2xl font-semibold mt-1">{{ $plans }}</div>
            </div>

        </div>

        <!-- Second Row -->
        <div class="grid lg:grid-cols-2 gap-4">

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-5 rounded-xl">
                <h2 class="text-sm font-semibold mb-2">Support Tickets</h2>
                <div class="text-3xl font-semibold">{{ $supportTickets }}</div>
            </div>

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-5 rounded-xl">
                <h2 class="text-sm font-semibold mb-2">Notifications</h2>
                <div class="text-3xl font-semibold">{{ $notifications }}</div>
            </div>

        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl">

            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
                <h2 class="text-sm font-semibold">Recent Users</h2>
            </div>

            <div class="divide-y divide-neutral-200 dark:divide-neutral-800">

                @foreach ($recentUsers as $user)
                    <div class="px-6 py-3 flex justify-between text-sm">

                        <div>
                            {{ $user->first_name }} {{ $user->last_name }}
                        </div>

                        <div class="text-neutral-400">
                            {{ $user->email }}
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

@endsection

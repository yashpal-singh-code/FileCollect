@extends('owner.layouts.app')

@section('title', 'Tenant Profile')

@section('owner_content')

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                    Tenant Profile
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    View tenant account, company, and subscription information
                </p>
            </div>

            <a href="{{ route('owner.users.index') }}"
                class="inline-flex items-center gap-2 h-9 px-4 text-sm border
           text-neutral-600 border-neutral-300 bg-neutral-100
           hover:bg-neutral-200
           dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800">

                <x-lucide-arrow-left class="w-4 h-4" />
                Back
            </a>

        </div>

        {{-- TENANT USAGE SUMMARY --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="border p-4 bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800">
                <p class="text-xs text-neutral-500">Plan</p>
                <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ $activePlan->name ?? 'Free Plan' }}
                </p>
            </div>

            <div class="border p-4 bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800">
                <p class="text-xs text-neutral-500">Storage</p>
                <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ number_format(($user->storage_used ?? 0) / 1024 / 1024, 2) }} MB
                    /
                    {{ $activePlan->storage_mb ?? 0 }} MB
                </p>
            </div>

            <div class="border p-4 bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800">
                <p class="text-xs text-neutral-500">Users</p>
                <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ $totalUsers }} / {{ $activePlan->company_users ?? '∞' }}
                </p>
            </div>

            <div class="border p-4 bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800">
                <p class="text-xs text-neutral-500">Documents</p>
                <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ $totalDocuments }} / {{ $activePlan->document_requests ?? '∞' }}
                </p>
            </div>

        </div>

        {{-- SUBSCRIPTION INFO (RAZORPAY) --}}
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6">

            <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                Subscription Information
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                {{-- STATUS --}}
                <div>
                    <p class="text-xs text-neutral-500">Status</p>

                    @if ($isSubscribed)
                        <span
                            class="inline-flex px-2 py-0.5 text-xs border rounded-full
                        text-emerald-600 border-emerald-600/30 bg-emerald-600/10
                        dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10">
                            Active
                        </span>
                    @else
                        <span
                            class="inline-flex px-2 py-0.5 text-xs border rounded-full
                        text-neutral-600 border-neutral-300 bg-neutral-100
                        dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800">
                            Free
                        </span>
                    @endif
                </div>

                {{-- BILLING --}}
                <div>
                    <p class="text-xs text-neutral-500">Billing Cycle</p>
                    <p class="text-sm text-neutral-900 dark:text-neutral-100">
                        {{ $isSubscribed ? ucfirst($activeBilling ?? '-') : 'Free' }}
                    </p>
                </div>

                {{-- STARTED --}}
                <div>
                    <p class="text-xs text-neutral-500">Started</p>
                    <p class="text-sm text-neutral-900 dark:text-neutral-100">
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>

                {{-- NEXT BILLING --}}
                <div>
                    <p class="text-xs text-neutral-500">Next Billing</p>
                    <p class="text-sm text-neutral-900 dark:text-neutral-100">
                        {{ $currentPeriodEnd ? \Carbon\Carbon::parse($currentPeriodEnd)->format('d M Y') : '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- COMPANY --}}
        <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6">

            <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                Company Information
            </h2>

            @if ($user->companySetting)

                <div class="grid md:grid-cols-4 gap-6">

                    <div>
                        @if ($user->companySetting->company_logo)
                            <img src="{{ asset('storage/' . $user->companySetting->company_logo) }}" class="h-16">
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">Company Name</p>
                        <p class="text-sm font-medium">
                            {{ $user->companySetting->company_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">Email</p>
                        <p class="text-sm">
                            {{ $user->companySetting->email ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">Phone</p>
                        <p class="text-sm">
                            {{ $user->companySetting->phone ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">Address</p>
                        <p class="text-sm">
                            {{ $user->companySetting->address_line_1 ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">City</p>
                        <p class="text-sm">
                            {{ $user->companySetting->city ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">State</p>
                        <p class="text-sm">
                            {{ $user->companySetting->state ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500">Country</p>
                        <p class="text-sm">
                            {{ $user->companySetting->country ?? '-' }}
                        </p>
                    </div>

                </div>
            @else
                <p class="text-sm text-neutral-500">
                    No company information found.
                </p>
            @endif

        </div>

    </div>

@endsection

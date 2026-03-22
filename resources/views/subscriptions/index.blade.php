@extends('layouts.app')

@section('title', 'Subscriptions')
@section('description', 'Manage your subscription and billing.')

@section('content')

    <div x-data="{ billing: '{{ $activeBilling ?? 'monthly' }}', cancelModal: false }" class="w-full px-4 sm:px-6 lg:px-8 py-6">

        <div class="w-full bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <x-lucide-wallet class="w-6 h-6 text-primary-600" />
                    <div>
                        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                            Subscription & Billing
                        </h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Manage your plan and billing
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-12">

                {{-- ================= CURRENT PLAN ================= --}}
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-neutral-900 dark:text-white">
                        Current Plan
                    </h2>

                    <div class="border border-primary-500/30 bg-primary-50 dark:bg-neutral-800 p-6">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

                            {{-- LEFT --}}
                            <div class="space-y-2">

                                <div class="flex items-center gap-3 flex-wrap">

                                    <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-400">
                                        {{ $subscription && $subscription->status === 'active' && $activePlan && !$activePlan->isFree() ? $activePlan->name : 'Free Plan' }}
                                    </h3>

                                    @if ($subscription && $subscription->status === 'active' && $activePlan && !$activePlan->isFree())
                                        <span
                                            class="px-2 py-0.5 text-xs border text-emerald-600 border-emerald-600/30 bg-emerald-600/10">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 text-xs border text-neutral-600 dark:text-neutral-300 border-neutral-300 bg-neutral-100 dark:bg-neutral-700">
                                            Free
                                        </span>
                                    @endif

                                </div>

                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    @if ($subscription && $subscription->status === 'active' && $activePlan && !$activePlan->isFree())
                                        @if ($activeBilling === 'yearly')
                                            ₹{{ number_format($activePlan->yearly_price) }} / year
                                        @else
                                            ₹{{ number_format($activePlan->monthly_price) }} / month
                                        @endif
                                    @else
                                        Free Forever
                                    @endif
                                </p>

                                @if ($subscription && $subscription->status === 'active' && $nextBillingDate)
                                    <p class="text-xs text-neutral-500">
                                        Renews on {{ $nextBillingDate->format('d M Y') }}
                                    </p>
                                @endif

                            </div>

                            {{-- ACTION --}}
                            @if ($subscription && $subscription->status === 'active')
                                <button @click="cancelModal = true"
                                    class="h-10 px-5 text-sm font-medium border
                                text-red-600 border-red-600/30 bg-red-600/10
                                hover:bg-red-600 hover:text-white transition cursor-pointer">

                                    <x-lucide-x-circle class="w-4 h-4 inline mr-1" />
                                    Cancel
                                </button>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- ================= AVAILABLE PLANS ================= --}}
                <div>

                    <h2 class="text-lg font-semibold mb-6 text-neutral-900 dark:text-white">
                        Available Plans
                    </h2>

                    {{-- TOGGLE --}}
                    <div class="flex justify-center mb-8">
                        <div class="p-1 flex border bg-neutral-100 dark:bg-neutral-800 dark:border-neutral-700">

                            <button @click="billing='monthly'"
                                :class="billing === 'monthly' ? 'bg-primary-600 text-white' : ''"
                                class="px-5 py-2 text-sm cursor-pointer">
                                Monthly
                            </button>

                            <button @click="billing='yearly'"
                                :class="billing === 'yearly' ? 'bg-primary-600 text-white' : ''"
                                class="px-5 py-2 text-sm cursor-pointer">
                                Yearly
                            </button>

                        </div>
                    </div>

                    {{-- GRID --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                        @foreach ($plans as $plan)
                            @php $isCurrent = $activePlan && $activePlan->id === $plan->id; @endphp

                            <div
                                class="p-6 border dark:border-neutral-700
                            {{ $isCurrent ? 'border-primary-500 bg-primary-50 dark:bg-neutral-800' : 'bg-white dark:bg-neutral-900 hover:bg-neutral-50 dark:hover:bg-neutral-800' }}">

                                <h3 class="font-semibold mb-2 text-neutral-900 dark:text-white">
                                    {{ $plan->name }}
                                </h3>

                                @if ($plan->isFree())
                                    <p class="text-2xl font-bold">Free</p>
                                @else
                                    <p class="text-2xl font-bold"
                                        x-text="billing==='monthly'
                                    ? '₹{{ number_format($plan->monthly_price) }}'
                                    : '₹{{ number_format($plan->yearly_price) }}'">
                                    </p>
                                    <p class="text-xs text-neutral-500 mt-1">
                                        per <span x-text="billing"></span>
                                    </p>
                                @endif

                                @if (!$isCurrent)
                                    <button
                                        @click="window.location.href = '/select-plan?plan={{ $plan->slug }}&billing=' + billing"
                                        class="w-full mt-5 py-2 text-sm font-medium
                                    bg-primary-600 text-white hover:bg-primary-700 transition cursor-pointer">

                                        Upgrade / Downgrade
                                    </button>
                                @else
                                    <p class="mt-5 text-xs text-green-600">Current Plan</p>
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- ================= INVOICES ================= --}}
                <div>
                    <h2 class="text-lg font-semibold mb-4 text-neutral-900 dark:text-white">
                        Invoices
                    </h2>

                    <div class="border border-neutral-200 dark:border-neutral-800 divide-y">

                        @forelse ($invoices as $invoice)
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">

                                <div>
                                    <p class="text-sm font-semibold text-neutral-900 dark:text-white">
                                        {{ $invoice->invoice_number }}
                                    </p>

                                    <p class="text-xs text-neutral-500">
                                        ₹{{ number_format($invoice->amount) }}
                                        • {{ $invoice->paid_at?->format('d M Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">

                                    <span
                                        class="text-xs px-2 py-0.5 border
                                    {{ $invoice->status === 'paid'
                                        ? 'text-green-600 border-green-600/30 bg-green-600/10'
                                        : 'text-yellow-600 border-yellow-600/30 bg-yellow-600/10' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>

                                    <a href="{{ route('invoice.download', $invoice->id) }}"
                                        class="px-3 py-1.5 text-xs font-medium
                                    bg-primary-600 text-white hover:bg-primary-700 transition cursor-pointer">
                                        Download PDF
                                    </a>

                                </div>

                            </div>
                        @empty
                            <div class="p-4 text-sm text-neutral-500">
                                No invoices yet.
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>
        </div>

        {{-- CANCEL MODAL --}}
        <div x-show="cancelModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

            <div class="bg-white dark:bg-neutral-900 w-full max-w-md p-6 border dark:border-neutral-700">

                <h2 class="text-lg font-semibold mb-2 text-neutral-900 dark:text-white">
                    Cancel Subscription
                </h2>

                <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">
                    You will be moved to Free Plan. You can upgrade anytime.
                </p>

                <div class="flex justify-end gap-2">

                    <button @click="cancelModal = false" class="px-4 py-2 text-sm border cursor-pointer">
                        Close
                    </button>

                    <form method="POST" action="{{ route('subscriptions.cancel') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-red-600 text-white hover:bg-red-700 cursor-pointer">
                            Yes, Cancel
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>

@endsection

@extends('layouts.app')

@section('title', 'Subscriptions')
@section('description', 'Manage your subscription and billing.')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-8 py-6">

        <div class="w-full bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <x-lucide-wallet class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Subscription & Billing
                        </h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                            Manage your plan, billing and invoices
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-12">

                {{-- ================= CURRENT PLAN ================= --}}
                <div>

                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4">
                        Current Plan
                    </h2>

                    <div class="border border-primary-500/30 bg-primary-50 dark:bg-primary-900/20 p-6">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                            {{-- LEFT SIDE --}}
                            <div class="flex flex-col gap-2">

                                <div class="flex items-center gap-3">

                                    <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-400">
                                        {{ $activePlan->name ?? 'Free Plan' }}
                                    </h3>

                                    {{-- STATUS --}}
                                    @if ($subscription && !$subscription->ended())

                                        @if ($subscription->onGracePeriod())
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium border
                            text-yellow-700 border-yellow-500/30 bg-yellow-500/10">
                                                Cancelling
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium border
    text-emerald-600 border-emerald-600/30 bg-emerald-600/10
    dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10">
                                                Active
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium border
                        text-neutral-700 border-neutral-300 bg-neutral-100">
                                            Free
                                        </span>

                                    @endif

                                </div>


                                {{-- PRICE --}}
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">

                                    @if ($activePlan)

                                        @if ($activeBilling === 'yearly')
                                            ₹{{ number_format($activePlan->yearly_price, 0) }} / year
                                        @else
                                            ₹{{ number_format($activePlan->monthly_price, 0) }} / month
                                        @endif
                                    @else
                                        Free Forever
                                    @endif

                                </p>


                                {{-- RENEWAL --}}
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">

                                    @if ($currentPeriodEnd && !$subscription->onGracePeriod())
                                        Renews on
                                        <span class="font-medium text-neutral-700 dark:text-neutral-300">
                                            {{ \Carbon\Carbon::createFromTimestamp($currentPeriodEnd)->format('d M Y') }}
                                        </span>
                                    @endif


                                    @if ($subscription && $subscription->onGracePeriod() && $subscription->ends_at)
                                        Ends on
                                        <span class="font-medium text-neutral-700 dark:text-neutral-300">
                                            {{ $subscription->ends_at->format('d M Y') }}
                                        </span>
                                    @endif

                                </div>

                            </div>



                            {{-- RIGHT SIDE --}}
                            <div class="flex items-center gap-2">

                                {{-- CANCEL --}}
                                @if ($subscription && !$subscription->onGracePeriod() && !$subscription->ended())
                                    <button type="button" @click="$dispatch('open-cancel-subscription')"
                                        class="h-9 px-4 text-sm font-medium border
        inline-flex items-center gap-1.5
        text-red-600 border-red-600/30 bg-red-600/10
        hover:bg-red-600 hover:text-white
        dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
        dark:hover:bg-red-500 dark:hover:text-white
        transition cursor-pointer">

                                        <x-lucide-x-circle class="w-4 h-4" />
                                        Cancel
                                    </button>
                                @endif

                                {{-- RESUME --}}
                                @if ($subscription && $subscription->onGracePeriod())
                                    <form method="POST" action="{{ route('subscriptions.resume') }}">
                                        @csrf

                                        <button type="submit"
                                            class="h-9 px-4 text-sm font-medium border
                            text-primary-600 border-primary-600/30 bg-primary-600/10
                            hover:bg-primary-600 hover:text-white
                            transition cursor-pointer">

                                            Resume

                                        </button>

                                    </form>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================= CANCEL SUBSCRIPTION MODAL ================= --}}
                <div x-data="{ show: false }" x-on:open-cancel-subscription.window="show=true" x-show="show" x-cloak
                    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-2 sm:p-4">

                    <div @click.away="show=false"
                        class="w-full sm:max-w-md
bg-white dark:bg-neutral-900
border border-neutral-200 dark:border-neutral-800
p-6">

                        {{-- Title --}}
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                            Cancel Subscription
                        </h2>

                        {{-- Description --}}
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                            You are about to cancel your subscription.
                        </p>

                        {{-- Warning --}}
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-3">
                            ⚠ Your plan will remain active until the end of the billing period.
                        </p>

                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-2">
                            After that, your account will automatically downgrade to the Free plan.
                        </p>

                        {{-- Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-2 mt-6">

                            <button type="button" @click="show=false"
                                class="w-full h-10
border border-neutral-300 dark:border-neutral-700
text-sm font-medium
hover:bg-neutral-100 dark:hover:bg-neutral-800
transition cursor-pointer">

                                Keep Plan

                            </button>

                            <form method="POST" action="{{ route('subscriptions.cancel') }}" class="w-full">
                                @csrf

                                <button type="submit"
                                    class="w-full h-10
text-white text-sm font-medium
bg-red-600 hover:bg-red-700
transition cursor-pointer">

                                    Confirm Cancel

                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- ================= AVAILABLE PLANS ================= --}}
                <div x-data="{ billing: '{{ $activeBilling ?? 'monthly' }}' }">

                    <h2 class="text-lg font-semibold mb-4">Available Plans</h2>
                    {{-- Billing Toggle --}}
                    <div class="flex justify-center mb-6">
                        <div
                            class="p-1 flex border
        border-neutral-300 bg-neutral-100
        dark:border-neutral-700 dark:bg-neutral-800">

                            <button @click="billing = 'monthly'"
                                :class="billing === 'monthly'
                                    ?
                                    'bg-primary-600 text-white' :
                                    'text-neutral-700 hover:bg-neutral-200 dark:text-neutral-300 dark:hover:bg-neutral-700'"
                                class="px-5 py-2 text-sm font-medium transition cursor-pointer">

                                Monthly

                            </button>

                            <button @click="billing = 'yearly'"
                                :class="billing === 'yearly'
                                    ?
                                    'bg-primary-600 text-white' :
                                    'text-neutral-700 hover:bg-neutral-200 dark:text-neutral-300 dark:hover:bg-neutral-700'"
                                class="px-5 py-2 text-sm font-medium transition cursor-pointer">

                                Yearly

                            </button>

                        </div>
                    </div>

                    {{-- Plans Row --}}
                    <div class="flex gap-4 overflow-x-auto pb-2">

                        @foreach ($plans as $plan)
                            @php
                                if ($subscription && $subscription->valid()) {
                                    $isActive = $activePlan && $activePlan->id === $plan->id;
                                } else {
                                    $isActive = $plan->isFree();
                                }
                            @endphp

                            <div
                                class="min-w-60 shrink-0
           p-5 border
           {{ $isActive
               ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20'
               : 'border-neutral-200 dark:border-neutral-800' }}">

                                <div class="flex justify-between items-center mb-3">

                                    <h3 class="text-base font-semibold">
                                        {{ $plan->name }}
                                    </h3>

                                    @if ($isActive)
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700">
                                            Active
                                        </span>
                                    @endif

                                </div>

                                {{-- Price --}}
                                @if ($plan->isFree())
                                    <p class="text-2xl font-bold mb-4">Free</p>
                                @else
                                    <div class="mb-4">
                                        <p class="text-2xl font-bold"
                                            x-text="billing === 'monthly'
                                ? '₹{{ number_format($plan->monthly_price, 0) }}'
                                : '₹{{ number_format($plan->yearly_price ?? 0, 0) }}'">
                                        </p>

                                        <span class="text-xs text-neutral-500"
                                            x-text="billing === 'monthly' ? '/month' : '/year'">
                                        </span>
                                    </div>
                                @endif

                                {{-- Action --}}
                                @if (!$isActive)
                                    @if ($plan->isFree())
                                        <form method="GET" action="{{ route('subscriptions.checkout') }}">
                                            <input type="hidden" name="plan" value="{{ $plan->slug }}">
                                            <input type="hidden" name="billing" value="monthly">

                                            <button type="submit"
                                                class="w-full bg-neutral-900 text-white py-2 text-sm hover:bg-neutral-800 transition cursor-pointer">
                                                Switch to Free
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('subscriptions.swap') }}">
                                            @csrf
                                            <input type="hidden" name="plan" value="{{ $plan->slug }}">
                                            <input type="hidden" name="billing" x-bind:value="billing">

                                            <button type="submit"
                                                class="w-full h-9 px-4 inline-flex items-center justify-center gap-1.5
    text-sm font-medium border
    text-primary-600 border-primary-600/30 bg-primary-600/10
    hover:bg-primary-600 hover:text-white
    dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
    dark:hover:bg-primary-500 dark:hover:text-white
    transition cursor-pointer">

                                                <x-lucide-arrow-up-circle class="w-4 h-4" />
                                                Upgrade / Downgrade

                                            </button>
                                        </form>
                                    @endif
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>


                {{-- ================= PAYMENT METHODS ================= --}}
                <div>

                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4">
                        Payment Methods
                    </h2>

                    <div class="border border-neutral-200 dark:border-neutral-800 divide-y">

                        @forelse ($paymentMethods as $method)
                            <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                {{-- CARD DETAILS --}}
                                <div class="flex items-center gap-4">

                                    {{-- CARD ICON --}}
                                    <div
                                        class="w-10 h-10 flex items-center justify-center
                        bg-neutral-100 dark:bg-neutral-800
                        border border-neutral-200 dark:border-neutral-700">

                                        <x-lucide-credit-card class="w-5 h-5 text-neutral-500" />

                                    </div>


                                    <div>

                                        <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">

                                            {{ ucfirst($method->card->brand) }}
                                            <span class="text-neutral-500">
                                                •••• {{ $method->card->last4 }}
                                            </span>

                                        </p>

                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">

                                            Expires
                                            {{ str_pad($method->card->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $method->card->exp_year }}

                                        </p>

                                    </div>

                                </div>


                                {{-- ACTIONS --}}
                                <div class="flex items-center gap-2 flex-wrap">

                                    {{-- DEFAULT BADGE --}}
                                    @if ($paymentMethod && $paymentMethod->id === $method->id)
                                        <span
                                            class="h-8 px-3 inline-flex items-center gap-1.5
            text-xs font-medium border
            text-emerald-700 border-emerald-500/30 bg-emerald-500/10
            dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10">

                                            <x-lucide-check class="w-3.5 h-3.5" />
                                            Default

                                        </span>
                                    @else
                                        {{-- SET DEFAULT --}}
                                        <form method="POST" action="{{ route('billing.default') }}">
                                            @csrf

                                            <input type="hidden" name="payment_method" value="{{ $method->id }}">

                                            <button type="submit"
                                                class="h-8 px-3 inline-flex items-center gap-1.5
                text-xs font-medium border
                text-primary-600 border-primary-600/30 bg-primary-600/10
                hover:bg-primary-600 hover:text-white
                dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                dark:hover:bg-primary-500 dark:hover:text-white
                transition cursor-pointer">

                                                <x-lucide-star class="w-3.5 h-3.5" />
                                                Set Default

                                            </button>

                                        </form>
                                    @endif



                                    {{-- REMOVE CARD --}}
                                    @if (!$paymentMethod || $paymentMethod->id !== $method->id)
                                        <form method="POST" action="{{ route('billing.remove') }}">
                                            @csrf
                                            @method('DELETE')

                                            <input type="hidden" name="payment_method" value="{{ $method->id }}">

                                            <button type="submit"
                                                class="h-8 px-3 inline-flex items-center gap-1.5
                text-xs font-medium border
                text-red-600 border-red-600/30 bg-red-600/10
                hover:bg-red-600 hover:text-white
                dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
                dark:hover:bg-red-500 dark:hover:text-white
                transition cursor-pointer">

                                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                Remove

                                            </button>

                                        </form>
                                    @endif



                                    {{-- STRIPE PORTAL --}}
                                    <a href="{{ route('subscriptions.portal') }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="h-8 px-3 inline-flex items-center gap-1.5
        text-xs font-medium border
        text-neutral-700 border-neutral-300 bg-neutral-100
        hover:bg-neutral-200 hover:text-neutral-900
        dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
        dark:hover:bg-neutral-700 dark:hover:text-white
        transition cursor-pointer">

                                        <x-lucide-external-link class="w-3.5 h-3.5" />
                                        Manage

                                    </a>

                                </div>

                            </div>

                        @empty

                            {{-- EMPTY STATE --}}
                            <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                    No payment methods added.
                                </p>

                                @if ($subscription && $subscription->valid())
                                    <a href="{{ route('subscriptions.portal') }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="h-9 px-4 inline-flex items-center gap-2
                        text-sm font-medium border
                        text-primary-600 border-primary-600/30 bg-primary-600/10
                        hover:bg-primary-600 hover:text-white
                        transition cursor-pointer">

                                        <x-lucide-plus class="w-4 h-4" />
                                        Add Card

                                    </a>
                                @endif

                            </div>
                        @endforelse

                    </div>

                </div>


                {{-- ================= BILLING HISTORY ================= --}}
                <div>

                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4">
                        Billing History
                    </h2>

                    <div
                        class="overflow-x-auto bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">

                        <table class="w-full text-sm text-left">

                            <thead class="bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">
                                <tr>
                                    <th class="px-4 py-3 w-16 text-center">#</th>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">

                                @forelse($invoices as $invoice)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition">

                                        {{-- SR NUMBER --}}
                                        <td class="px-4 py-3 text-center text-neutral-500">
                                            {{ $loop->iteration }}
                                        </td>

                                        {{-- Invoice --}}
                                        <td class="px-4 py-3 font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ $invoice->number ?? '—' }}
                                        </td>

                                        {{-- Amount --}}
                                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                            ₹{{ $invoice->total() }}
                                        </td>
                                        {{-- Date --}}
                                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                            {{ $invoice->date()->format('d M Y') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-4 py-3">

                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium border
        {{ $invoice->status === 'paid'
            ? 'text-emerald-700 border-emerald-500/30 bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10'
            : 'text-yellow-700 border-yellow-500/30 bg-yellow-500/10 dark:text-yellow-400 dark:border-yellow-400/40 dark:bg-yellow-400/10' }}">

                                                {{ ucfirst($invoice->status) }}

                                            </span>

                                        </td>
                                        {{-- Actions --}}
                                        <td class="px-4 py-3 text-right">

                                            <div class="flex justify-end gap-2">

                                                {{-- Download --}}
                                                <a href="{{ $invoice->invoice_pdf }}" target="_blank"
                                                    class="h-8 px-3 inline-flex items-center gap-1.5
            text-xs font-medium border
            text-neutral-700 border-neutral-300 bg-neutral-100
            hover:bg-neutral-200 hover:text-neutral-900
            dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
            dark:hover:bg-neutral-700 dark:hover:text-white
            transition cursor-pointer">

                                                    <x-lucide-download class="w-3.5 h-3.5" />
                                                    Download

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6"
                                            class="px-4 py-10 text-center text-neutral-500 dark:text-neutral-400 italic">

                                            No invoices found.

                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection

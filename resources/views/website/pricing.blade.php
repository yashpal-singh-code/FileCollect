@extends('website.layouts.app')

@section('website')
    <section class="bg-slate-50 dark:bg-[#020617] py-14 sm:py-16 lg:py-20" x-data="{ billing: 'monthly', loading: false }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center mb-12">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    Pricing Plans
                </h1>
                <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-400">
                    Choose the plan that fits your workflow.
                </p>
            </div>

            {{-- TOGGLE --}}
            <div class="flex justify-center mb-10">
                <div class="bg-white border rounded-full p-1 flex">

                    <button @click="billing = 'monthly'"
                        :class="billing === 'monthly' ? 'bg-blue-600 text-white' : 'text-gray-600'"
                        class="px-5 py-2 rounded-full text-sm">
                        Monthly
                    </button>

                    <button @click="billing = 'yearly'"
                        :class="billing === 'yearly' ? 'bg-blue-600 text-white' : 'text-gray-600'"
                        class="px-5 py-2 rounded-full text-sm">
                        Yearly
                    </button>

                </div>
            </div>

            {{-- PLANS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach ($plans as $plan)
                    <div
                        class="p-6 rounded-2xl border bg-white shadow hover:shadow-xl transition flex flex-col
                    {{ $plan->is_popular ? 'border-blue-600 scale-105' : '' }}">

                        <h3 class="font-semibold text-lg mb-2">{{ $plan->name }}</h3>

                        <div class="mb-4 text-3xl font-bold">
                            ₹{{ $plan->monthly_price }}
                        </div>

                        <ul class="text-sm space-y-2 mb-6">
                            <li>{{ $plan->company_users }} Users</li>
                            <li>{{ $plan->clients }} Clients</li>
                            <li>{{ $plan->document_requests }} Requests</li>
                        </ul>

                        <button class="mt-auto bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition">
                            Choose Plan
                        </button>

                    </div>
                @endforeach

            </div>

        </div>

    </section>
@endsection

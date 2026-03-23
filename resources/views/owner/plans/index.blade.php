@extends('owner.layouts.app')

@section('title', 'Plans')

@section('owner_content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                    Plans
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Manage your SaaS subscription plans
                </p>
            </div>

            {{-- CREATE BUTTON --}}
            <a href="{{ route('owner.plans.create') }}"
                class="w-full lg:w-auto h-9 px-5
                  inline-flex items-center justify-center gap-2
                  text-sm font-medium border
                  text-primary-600 border-primary-600/30 bg-primary-600/10
                  hover:bg-primary-600 hover:text-white
                  dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                  dark:hover:bg-primary-500 dark:hover:text-white
                  transition">

                <x-lucide-plus class="w-4 h-4" />

                New Plan

            </a>

        </div>


        {{-- EMPTY STATE --}}
        @if ($plans->count() === 0)

            <div class="border border-dashed border-neutral-300 dark:border-neutral-700 p-12 text-center">

                <x-lucide-package class="w-12 h-12 mx-auto text-neutral-400 mb-4" />

                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">
                    No plans created
                </h3>

                <p class="text-sm text-neutral-500 mt-2 mb-6">
                    Create your first SaaS subscription plan.
                </p>

                <a href="{{ route('owner.plans.create') }}"
                    class="inline-flex items-center gap-2 px-5 h-9
                      text-sm font-medium border
                      text-primary-600 border-primary-600/30 bg-primary-600/10
                      hover:bg-primary-600 hover:text-white
                      dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                      dark:hover:bg-primary-500 dark:hover:text-white
                      transition">

                    <x-lucide-plus class="w-4 h-4" />

                    Create Plan

                </a>

            </div>
        @else
            {{-- GRID --}}
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">

                @foreach ($plans as $plan)
                    <div
                        class="bg-white dark:bg-neutral-900
                        border border-neutral-200 dark:border-neutral-800
                        p-4 flex flex-col justify-between">

                        {{-- TOP --}}
                        <div>

                            {{-- NAME --}}
                            <h3 class="font-semibold text-neutral-900 dark:text-neutral-100">

                                {{ $plan->name }}

                            </h3>


                            {{-- PRICE --}}
                            <div class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">

                                @if ($plan->is_free)
                                    Free Plan
                                @else
                                    ₹{{ $plan->monthly_price }} /mo

                                    <span class="text-xs text-neutral-400">
                                        (₹{{ $plan->yearly_price }} yearly)
                                    </span>
                                @endif

                            </div>


                            {{-- META BADGES --}}
                            <div class="flex flex-wrap gap-2 mt-3 text-xs">

                                {{-- STORAGE --}}
                                <span
                                    class="px-2 py-0.5 border
                                     border-neutral-300 dark:border-neutral-700
                                     bg-neutral-100 dark:bg-neutral-800
                                     text-neutral-600 dark:text-neutral-400">

                                    {{ $plan->storage_mb }}MB storage

                                </span>


                                {{-- USERS --}}
                                @if ($plan->company_users)
                                    <span
                                        class="px-2 py-0.5 border
                                     border-neutral-300 dark:border-neutral-700
                                     bg-neutral-100 dark:bg-neutral-800
                                     text-neutral-600 dark:text-neutral-400">

                                        {{ $plan->company_users }} users

                                    </span>
                                @endif


                                {{-- POPULAR --}}
                                @if ($plan->is_popular)
                                    <span
                                        class="px-2 py-0.5 border
                                         border-purple-500/30
                                         bg-purple-500/10
                                         text-purple-600 dark:text-purple-400">

                                        ⭐ Popular

                                    </span>
                                @endif


                                {{-- ACTIVE --}}
                                @if ($plan->is_active)
                                    <span
                                        class="px-2 py-0.5 border
                                         border-emerald-500/30
                                         bg-emerald-500/10
                                         text-emerald-600 dark:text-emerald-400">

                                        Active

                                    </span>
                                @else
                                    <span
                                        class="px-2 py-0.5 border
                                         border-neutral-300 dark:border-neutral-700
                                         bg-neutral-100 dark:bg-neutral-800
                                         text-neutral-500">

                                        Disabled

                                    </span>
                                @endif

                            </div>

                        </div>


                        {{-- ACTIONS --}}
                        <div class="flex gap-2 mt-4">

                            <a href="{{ route('owner.plans.edit', $plan) }}"
                                class="flex-1 h-9 inline-flex items-center justify-center gap-1.5
                              text-xs font-medium
                              border
                              text-emerald-600 border-emerald-600/30 bg-emerald-600/10
                              hover:bg-emerald-600 hover:text-white
                              dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10
                              dark:hover:bg-emerald-500 dark:hover:text-white
                              transition">

                                <x-lucide-square-pen class="w-3.5 h-3.5" />

                                Edit

                            </a>


                            {{-- DELETE --}}
                            <form method="POST" action="{{ route('owner.plans.destroy', $plan) }}" class="flex-1">

                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Delete plan?')"
                                    class="w-full h-9 inline-flex items-center justify-center gap-1.5
                                   text-xs font-medium
                                   border
                                   text-red-600 border-red-600/30 bg-red-600/10
                                   hover:bg-red-600 hover:text-white
                                   dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
                                   dark:hover:bg-red-500 dark:hover:text-white
                                   transition">

                                    <x-lucide-trash-2 class="w-3.5 h-3.5" />

                                    Delete

                                </button>

                            </form>

                        </div>

                    </div>
                @endforeach

            </div>


            {{-- PAGINATION --}}
            <div class="mt-6">

                {{ $plans->links() }}

            </div>

        @endif

    </div>

@endsection

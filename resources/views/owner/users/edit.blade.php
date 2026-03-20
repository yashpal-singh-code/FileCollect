@extends('owner.layouts.app')

@section('title', 'Edit Tenant')
@section('description', 'Update tenant account information.')

@section('owner_content')

    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="max-w-4xl mx-auto">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-6">

                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                        Edit Tenant
                    </h1>

                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Update tenant account details.
                    </p>
                </div>

                <a href="{{ route('owner.users.index') }}"
                    class="inline-flex items-center gap-2
h-9 px-4 text-sm border
text-neutral-600 border-neutral-300 bg-neutral-100
hover:bg-neutral-200
dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800">

                    <x-lucide-arrow-left class="w-4 h-4" />
                    Back

                </a>

            </div>


            {{-- FORM --}}
            <form method="POST" action="{{ route('owner.users.update', $user) }}"
                class="border border-neutral-200 dark:border-neutral-800
bg-white dark:bg-neutral-900 p-6 space-y-6">

                @csrf
                @method('PUT')


                {{-- NAME --}}
                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                            First Name
                        </label>

                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500">

                    </div>


                    <div>

                        <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                            Last Name
                        </label>

                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                            class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500">

                    </div>

                </div>


                {{-- EMAIL --}}
                <div>

                    <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500">

                </div>


                {{-- PHONE --}}
                <div>

                    <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                        Phone
                    </label>

                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500">

                </div>


                {{-- PLAN + BILLING --}}
                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                            Plan
                        </label>

                        <select name="plan_id"
                            class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100">

                            <option value="">Select Plan</option>

                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" {{ $user->plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                            Billing Cycle
                        </label>

                        <select name="billing_cycle"
                            class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100">

                            <option value="monthly" {{ $user->billing_cycle == 'monthly' ? 'selected' : '' }}>
                                Monthly
                            </option>

                            <option value="yearly" {{ $user->billing_cycle == 'yearly' ? 'selected' : '' }}>
                                Yearly
                            </option>

                        </select>

                    </div>

                </div>


                {{-- STATUS --}}
                <div>

                    <label class="flex items-center gap-3">

                        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}
                            class="w-4 h-4">

                        <span class="text-sm text-neutral-700 dark:text-neutral-300">
                            Active Account
                        </span>

                    </label>

                </div>


                {{-- PASSWORD --}}
                <div>

                    <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                        New Password
                    </label>

                    <input type="password" name="password" placeholder="Leave blank to keep current password"
                        class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500">

                </div>


                {{-- BUTTONS --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">

                    <a href="{{ route('owner.users.index') }}"
                        class="inline-flex items-center justify-center
h-10 px-5 text-sm border
text-neutral-600 border-neutral-300 bg-neutral-100
hover:bg-neutral-200
dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800">

                        Cancel

                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center
h-10 px-6 text-sm font-medium
text-white bg-primary-600
hover:bg-primary-700">

                        Update Tenant

                    </button>

                </div>


            </form>

        </div>
    </div>

@endsection

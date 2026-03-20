@extends('layouts.app')

@section('title', 'Create Client')
@section('description', 'Add a new client account.')

@section('content')

    <div class="w-full">

        <div class="max-w-4xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                    Create Client
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Add a new client account.
                </p>

                <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">
                    A secure client portal invitation will be generated automatically.
                </p>
            </div>

            {{-- CARD --}}
            <div
                class="border border-neutral-200 dark:border-neutral-800
                    bg-white dark:bg-neutral-900 p-6">

                {{-- ERRORS --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 border border-red-500/30 bg-red-500/10">
                        <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
                    @csrf

                    {{-- BASIC INFO --}}
                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}"
                                placeholder="Enter client's first name" title="Client's first name" required autofocus
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Last Name
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                placeholder="Enter client's last name" title="Client's last name"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="Enter client's email address" title="Client's email address"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Phone
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                placeholder="Enter client's phone number" title="Client's phone number"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Company Name
                            </label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                placeholder="Enter company name" title="Company name of the client"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Status
                            </label>
                            <select name="status" title="Client account status"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition cursor-pointer">

                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>

                            </select>
                        </div>

                    </div>

                    {{-- ADDRESS --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">
                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Address Information
                        </h2>

                        <div class="grid md:grid-cols-2 gap-5">

                            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}"
                                placeholder="Street address, P.O. box" title="Primary address line"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="address_line_2" value="{{ old('address_line_2') }}"
                                placeholder="Apartment, suite, unit, building" title="Secondary address details"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Enter city"
                                title="City name"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="state" value="{{ old('state') }}"
                                placeholder="Enter state or province" title="State or province"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                placeholder="Enter postal or ZIP code" title="Postal or ZIP code"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="country" value="{{ old('country') }}" placeholder="Enter country"
                                title="Country name"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                        </div>
                    </div>

                    {{-- NOTES --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">
                        <textarea name="notes" placeholder="Add internal notes about this client (optional)"
                            title="Internal notes visible only to your team"
                            class="w-full h-24 px-3 py-2 text-sm
                        bg-white dark:bg-neutral-800
                        border border-neutral-300 dark:border-neutral-700
                        text-neutral-900 dark:text-neutral-100
                        focus:outline-none focus:border-primary-500 transition">{{ old('notes') }}</textarea>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-3 pt-6">

                        <button type="submit"
                            class="w-full sm:w-auto
               inline-flex items-center justify-center gap-2
               h-10 px-5 text-sm font-medium
               border
               text-primary-600 border-primary-600/30 bg-primary-600/10
               hover:bg-primary-600 hover:text-white
               dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
               dark:hover:bg-primary-500 dark:hover:text-white
               transition cursor-pointer">

                            <x-lucide-circle-plus class="w-4 h-4" />
                            Create Client
                        </button>

                        <a href="{{ route('clients.index') }}"
                            class="w-full sm:w-auto
               inline-flex items-center justify-center gap-2
               h-10 px-5 text-sm font-medium
               border
               text-neutral-600 border-neutral-300 bg-neutral-100
               hover:bg-neutral-200
               dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
               dark:hover:bg-neutral-700
               transition">

                            <x-lucide-circle-x class="w-4 h-4" />
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

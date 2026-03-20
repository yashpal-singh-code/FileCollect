@extends('layouts.app')

@section('title', 'Edit Client')
@section('description', 'Update client account details.')

@section('content')

    <div class="w-full">

        <div class="max-w-4xl mx-auto">

            {{-- HEADER --}}

            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                    Edit Client
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Update client account details.
                </p>
            </div>

            {{-- CARD --}}

            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6">


                <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">

                    @csrf
                    @method('PUT')

                    {{-- CLIENT INFORMATION --}}

                    <div>

                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Client Information
                        </h2>

                        <div class="grid md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                    First Name <span class="text-red-500">*</span>
                                </label>

                                <input type="text" name="first_name" value="{{ old('first_name', $client->first_name) }}"
                                    required autofocus
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

                                <input type="text" name="last_name" value="{{ old('last_name', $client->last_name) }}"
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

                                <input type="email" name="email" value="{{ old('email', $client->email) }}"
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

                                <input type="tel" name="phone" value="{{ old('phone', $client->phone) }}"
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

                                <input type="text" name="company_name"
                                    value="{{ old('company_name', $client->company_name) }}"
                                    class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                            </div>

                            <div>
                                <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                    Client Status
                                </label>

                                <select name="status"
                                    class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition cursor-pointer">

                                    <option value="active"
                                        {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="inactive"
                                        {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                    <option value="blocked"
                                        {{ old('status', $client->status) == 'blocked' ? 'selected' : '' }}>
                                        Blocked
                                    </option>

                                </select>
                            </div>

                        </div>

                    </div>

                    {{-- ADDRESS --}}

                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Address Information
                        </h2>

                        <div class="grid md:grid-cols-2 gap-5">

                            <input type="text" name="address_line_1"
                                value="{{ old('address_line_1', $client->address_line_1) }}" placeholder="Address Line 1"
                                class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="address_line_2"
                                value="{{ old('address_line_2', $client->address_line_2) }}" placeholder="Address Line 2"
                                class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="city" value="{{ old('city', $client->city) }}"
                                placeholder="City"
                                class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="state" value="{{ old('state', $client->state) }}"
                                placeholder="State"
                                class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="postal_code" value="{{ old('postal_code', $client->postal_code) }}"
                                placeholder="Postal Code"
                                class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                            <input type="text" name="country" value="{{ old('country', $client->country) }}"
                                placeholder="Country"
                                class="w-full h-10 px-3 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                        </div>

                    </div>

                    {{-- NOTES --}}

                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <textarea name="notes" placeholder="Internal notes about this client..."
                            class="w-full h-24 px-3 py-2 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">{{ old('notes', $client->notes) }}</textarea>

                    </div>

                    {{-- ACTIONS --}}

                    <div class="flex items-center gap-3 pt-6">

                        <button type="submit"
                            class="inline-flex items-center gap-2
h-10 px-5 text-sm font-medium border
text-primary-600 border-primary-600/30 bg-primary-600/10
hover:bg-primary-600 hover:text-white
dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
dark:hover:bg-primary-500 dark:hover:text-white
transition cursor-pointer">

                            <x-lucide-save class="w-4 h-4" />
                            Update Client

                        </button>

                        <a href="{{ route('clients.index') }}"
                            class="inline-flex items-center justify-center gap-2
    h-10 px-5 text-sm font-medium
    border
    text-neutral-600 border-neutral-300 bg-neutral-100
    hover:bg-neutral-200
    dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
    dark:hover:bg-neutral-700
    transition cursor-pointer">

                            <x-lucide-circle-x class="w-4 h-4" />
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

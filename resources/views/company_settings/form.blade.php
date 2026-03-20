@extends('layouts.app')

@section('title', 'Company Settings')
@section('description', 'Manage your company information and branding.')

@section('content')
    <div class="max-w-4xl mx-auto">

        <div class="bg-white dark:bg-neutral-900
            border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-start justify-between gap-4">

                    <div class="flex items-center gap-3">
                        <x-lucide-building class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                        <div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                                Company Settings
                            </h1>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                Manage your company information and branding
                            </p>
                        </div>
                    </div>

                    {{-- EDIT BUTTON --}}
                    @if ($mode === 'view')
                        <a href="{{ route('company-settings.show', ['edit' => 'true']) }}"
                            class="h-9 px-4 inline-flex items-center gap-2 text-sm font-semibold
                           border border-primary-600/30 text-primary-600 bg-primary-600/10
                           hover:bg-primary-600 hover:text-white
                           dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                           dark:hover:bg-primary-500 dark:hover:text-white
                           transition">
                            <x-lucide-square-pen class="w-4 h-4" />
                            Edit
                        </a>
                    @endif

                </div>
            </div>

            <div class="p-6">

                <form method="POST"
                    action="{{ $mode === 'create' ? route('company-settings.store') : route('company-settings.update') }}"
                    enctype="multipart/form-data" class="space-y-6">

                    @csrf
                    @if ($mode === 'edit')
                        @method('PUT')
                    @endif

                    @php
                        $disabled = $mode === 'view';
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- COMPANY NAME --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Company Name *
                            </label>
                            <input type="text" name="company_name"
                                value="{{ old('company_name', $company->company_name ?? '') }}"
                                placeholder="Enter company name" {{ $disabled ? 'disabled' : '' }} required
                                class="w-full h-9 px-3 text-sm
                                bg-white dark:bg-neutral-900
                                border border-neutral-300 dark:border-neutral-700
                                focus:outline-none focus:border-primary-500
                                transition
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- COMPANY LOGO --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Company Logo
                            </label>

                            @if ($company && $company->company_logo)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($company->company_logo) }}" class="h-12 object-contain">
                                </div>
                            @endif

                            @if (!$disabled)
                                <input type="file" name="company_logo"
                                    class="w-full h-9 px-3 text-sm
                                    bg-white dark:bg-neutral-900
                                    border border-neutral-300 dark:border-neutral-700
                                    focus:outline-none focus:border-primary-500
                                    transition">
                            @endif
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}"
                                placeholder="example@company.com" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm
                                bg-white dark:bg-neutral-900
                                border border-neutral-300 dark:border-neutral-700
                                focus:outline-none focus:border-primary-500
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- PHONE --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Phone
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $company->phone ?? '') }}"
                                placeholder="+91 98765 43210" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm
                                bg-white dark:bg-neutral-900
                                border border-neutral-300 dark:border-neutral-700
                                focus:outline-none focus:border-primary-500
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- ADDRESS LINE 1 --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Address Line 1
                            </label>
                            <input type="text" name="address_line_1"
                                value="{{ old('address_line_1', $company->address_line_1 ?? '') }}"
                                placeholder="Street address" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm border border-neutral-300 dark:border-neutral-700
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- ADDRESS LINE 2 --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Address Line 2
                            </label>
                            <input type="text" name="address_line_2"
                                value="{{ old('address_line_2', $company->address_line_2 ?? '') }}"
                                placeholder="Suite, Floor, etc." {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm border border-neutral-300 dark:border-neutral-700
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- CITY --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                City
                            </label>
                            <input type="text" name="city" value="{{ old('city', $company->city ?? '') }}"
                                placeholder="Enter city" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm border border-neutral-300 dark:border-neutral-700
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- STATE --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                State
                            </label>
                            <input type="text" name="state" value="{{ old('state', $company->state ?? '') }}"
                                placeholder="Enter state" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm border border-neutral-300 dark:border-neutral-700
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- POSTAL CODE --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Postal Code
                            </label>
                            <input type="text" name="postal_code"
                                value="{{ old('postal_code', $company->postal_code ?? '') }}"
                                placeholder="Enter postal code" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm border border-neutral-300 dark:border-neutral-700
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                        {{-- COUNTRY --}}
                        <div>
                            <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                Country
                            </label>
                            <input type="text" name="country" value="{{ old('country', $company->country ?? '') }}"
                                placeholder="Enter country" {{ $disabled ? 'disabled' : '' }}
                                class="w-full h-9 px-3 text-sm border border-neutral-300 dark:border-neutral-700
                                {{ $disabled ? 'opacity-70 cursor-not-allowed' : '' }}">
                        </div>

                    </div>

                    {{-- SAVE BUTTON --}}
                    @if (!$disabled)
                        <div class="pt-4">
                            <button type="submit"
                                class="h-9 px-5 inline-flex items-center gap-2 text-sm font-semibold
                                border text-primary-600 border-primary-600/30 bg-primary-600/10
                                hover:bg-primary-600 hover:text-white
                                dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                                dark:hover:bg-primary-500 dark:hover:text-white
                                transition cursor-pointer">
                                <x-lucide-save class="w-4 h-4" />
                                {{ $mode === 'create' ? 'Create Company' : 'Update Company' }}
                            </button>
                        </div>
                    @endif

                </form>

            </div>
        </div>
    </div>

@endsection

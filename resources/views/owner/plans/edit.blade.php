@extends('owner.layouts.app')

@section('title', 'Edit Plan')

@section('owner_content')

    <div class="w-full">
        <div class="max-w-4xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                    Edit Plan
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Update subscription plan.
                </p>

                <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">
                    Modify pricing, limits and available features.
                </p>
            </div>

            {{-- CARD --}}
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6">

                @if ($errors->any())
                    <div class="mb-5 p-4 border border-red-500/30 bg-red-500/10">
                        <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('owner.plans.update', $plan->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-2 gap-5">

                        {{-- BASIC --}}
                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Plan Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $plan->name) }}"
                                placeholder="e.g. Starter Plan"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Slug
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}"
                                placeholder="e.g. starter-plan"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        {{-- PRICING --}}
                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Monthly Price
                            </label>
                            <input type="number" step="0.01" name="monthly_price"
                                value="{{ old('monthly_price', $plan->monthly_price) }}" placeholder="e.g. 199"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Yearly Price
                            </label>
                            <input type="number" step="0.01" name="yearly_price"
                                value="{{ old('yearly_price', $plan->yearly_price) }}" placeholder="e.g. 1999"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Currency
                            </label>
                            <select name="currency"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                                <option value="INR" {{ old('currency', $plan->currency) == 'INR' ? 'selected' : '' }}>INR (₹)
                                </option>
                                <option value="USD" {{ old('currency', $plan->currency) == 'USD' ? 'selected' : '' }}>USD ($)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Sort Order
                            </label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}"
                                placeholder="e.g. 1"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="is_free" value="1"
                                    {{ old('is_free', $plan->is_free) ? 'checked' : '' }}> Free Plan
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="is_popular" value="1"
                                    {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }}> Popular Plan
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $plan->is_active) ? 'checked' : '' }}> Active
                            </label>
                        </div>

                        {{-- LIMITS --}}
                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">Company Users</label>
                            <input type="number" name="company_users"
                                value="{{ old('company_users', $plan->company_users) }}" placeholder="e.g. 5"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">Clients</label>
                            <input type="number" name="clients" value="{{ old('clients', $plan->clients) }}"
                                placeholder="e.g. 100"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">Document
                                Requests</label>
                            <input type="number" name="document_requests"
                                value="{{ old('document_requests', $plan->document_requests) }}" placeholder="e.g. 500"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">Template Limit</label>
                            <input type="number" name="template_limit"
                                value="{{ old('template_limit', $plan->template_limit) }}" placeholder="e.g. 20"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">Request
                                Templates</label>
                            <input type="number" name="request_templates"
                                value="{{ old('request_templates', $plan->request_templates) }}" placeholder="e.g. 10"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        {{-- STORAGE --}}
                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">Storage (MB)</label>
                            <input type="number" name="storage_mb" value="{{ old('storage_mb', $plan->storage_mb) }}"
                                placeholder="e.g. 100"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">File Size Limit
                                (MB)</label>
                            <input type="number" name="file_size_limit_mb"
                                value="{{ old('file_size_limit_mb', $plan->file_size_limit_mb) }}" placeholder="e.g. 10"
                                class="w-full h-10 px-3 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 text-neutral-900 dark:text-neutral-100 focus:outline-none focus:border-primary-500 transition">
                        </div>

                    </div>

                    {{-- FEATURES --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">
                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">Features</h2>

                        <div class="grid md:grid-cols-3 gap-4">

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="client_portal" value="1"
                                    {{ old('client_portal', $plan->client_portal) ? 'checked' : '' }}> Client Portal
                            </label>

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="mfa_authentication" value="1"
                                    {{ old('mfa_authentication', $plan->mfa_authentication) ? 'checked' : '' }}> MFA
                                Authentication
                            </label>

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="download_zip" value="1"
                                    {{ old('download_zip', $plan->download_zip) ? 'checked' : '' }}> Download ZIP
                            </label>

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="expiry_tracking" value="1"
                                    {{ old('expiry_tracking', $plan->expiry_tracking) ? 'checked' : '' }}> Expiry Tracking
                            </label>

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="branding" value="1"
                                    {{ old('branding', $plan->branding) ? 'checked' : '' }}> Branding
                            </label>

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="white_label" value="1"
                                    {{ old('white_label', $plan->white_label) ? 'checked' : '' }}> White Label
                            </label>

                            <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                <input type="checkbox" name="priority_support" value="1"
                                    {{ old('priority_support', $plan->priority_support) ? 'checked' : '' }}> Priority Support
                            </label>

                        </div>
                    </div>

                    {{-- SAFE JSON --}}
                    @php
                        $mimeTypes = old(
                            'allowed_mime_types',
                            $plan->allowed_mime_types ?? ['pdf', 'jpg', 'png', 'docx'],
                        );
                    @endphp

                    <input type="hidden" name="allowed_mime_types" value='@json($mimeTypes)'>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-3 pt-6">

                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-10 px-5 text-sm font-medium border text-primary-600 border-primary-600/30 bg-primary-600/10 hover:bg-primary-600 hover:text-white transition">
                            <x-lucide-save class="w-4 h-4" />
                            Update Plan
                        </button>

                        <a href="{{ route('owner.plans.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-10 px-5 text-sm font-medium border text-neutral-600 border-neutral-300 bg-neutral-100 hover:bg-neutral-200 transition">
                            <x-lucide-circle-x class="w-4 h-4" />
                            Cancel
                        </a>

                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

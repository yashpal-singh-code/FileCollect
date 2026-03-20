@extends('owner.layouts.app')

@section('title', 'Create Plan')

@section('owner_content')

    <div class="w-full">

        <div class="max-w-4xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                    Create Plan
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Add a new subscription plan.
                </p>

                <p class="text-xs text-neutral-500 dark:text-neutral-500 mt-1">
                    Configure pricing, limits and available features.
                </p>
            </div>

            {{-- CARD --}}
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6">

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

                <form method="POST" action="{{ route('owner.plans.store') }}" class="space-y-6">
                    @csrf

                    {{-- BASIC INFO --}}
                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Plan Name <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter plan name"
                                required autofocus
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Slug
                            </label>

                            <input type="text" name="slug" value="{{ old('slug') }}"
                                placeholder="example: starter-plan"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Monthly Price
                            </label>

                            <input type="number" step="0.01" name="monthly_price" value="{{ old('monthly_price') }}"
                                placeholder="0.00"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Yearly Price
                            </label>

                            <input type="number" step="0.01" name="yearly_price" value="{{ old('yearly_price') }}"
                                placeholder="0.00"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Currency
                            </label>

                            <select name="currency"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">

                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>
                                    USD
                                </option>

                            </select>
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Sort Order
                            </label>

                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Company Users
                            </label>

                            <input type="number" name="company_users" value="{{ old('company_users') }}" placeholder="10"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Clients
                            </label>

                            <input type="number" name="clients" value="{{ old('clients') }}" placeholder="100"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Document Requests
                            </label>

                            <input type="number" name="document_requests" value="{{ old('document_requests') }}"
                                placeholder="100"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100
                            focus:outline-none focus:border-primary-500 transition">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Template Limit
                            </label>

                            <input type="number" name="template_limit" value="{{ old('template_limit') }}"
                                placeholder="10"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Request Templates
                            </label>

                            <input type="number" name="request_templates" value="{{ old('request_templates') }}"
                                placeholder="10"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                WhatsApp Limit
                            </label>

                            <input type="number" name="whatsapp_limit" value="{{ old('whatsapp_limit') }}"
                                placeholder="100"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Storage (MB)
                            </label>

                            <input type="number" name="storage_mb" value="{{ old('storage_mb', 100) }}" placeholder="100"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                File Size Limit (MB)
                            </label>

                            <input type="number" name="file_size_limit_mb" value="{{ old('file_size_limit_mb', 10) }}"
                                placeholder="10"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Usage Reset
                            </label>

                            <select name="usage_reset_type"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">

                                <option value="monthly">Monthly</option>
                                <option value="none">None</option>

                            </select>
                        </div>

                    </div>

                    {{-- STRIPE --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Stripe
                        </h2>

                        <div class="grid md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                    Stripe Product ID
                                </label>

                                <input type="text" name="stripe_product_id" value="{{ old('stripe_product_id') }}"
                                    class="w-full h-10 px-3 text-sm
                                bg-white dark:bg-neutral-800
                                border border-neutral-300 dark:border-neutral-700">
                            </div>


                            <div>
                                <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                    Stripe Monthly Price ID
                                </label>

                                <input type="text" name="stripe_price_monthly"
                                    value="{{ old('stripe_price_monthly') }}"
                                    class="w-full h-10 px-3 text-sm
                                bg-white dark:bg-neutral-800
                                border border-neutral-300 dark:border-neutral-700">
                            </div>


                            <div>
                                <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                    Stripe Yearly Price ID
                                </label>

                                <input type="text" name="stripe_price_yearly"
                                    value="{{ old('stripe_price_yearly') }}"
                                    class="w-full h-10 px-3 text-sm
                                bg-white dark:bg-neutral-800
                                border border-neutral-300 dark:border-neutral-700">
                            </div>

                        </div>

                    </div>

                    {{-- UPLOAD FEATURES --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Upload Options
                        </h2>

                        <div class="grid md:grid-cols-3 gap-4">

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="allow_zip" value="1">
                                Allow ZIP
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="allow_video" value="1">
                                Allow Video
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="allow_multiple_uploads" value="1">
                                Multiple Uploads
                            </label>

                        </div>

                    </div>

                    {{-- FEATURES --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Features
                        </h2>

                        <div class="grid md:grid-cols-3 gap-4">

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="client_portal" value="1">
                                Client Portal
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="otp_login" value="1">
                                OTP Login
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="approve_workflow" value="1">
                                Approve Workflow
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="reupload_history" value="1">
                                Reupload History
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="download_zip" value="1">
                                Download ZIP
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="expiry_tracking" value="1">
                                Expiry Tracking
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="renewal_reminder" value="1">
                                Renewal Reminder
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="scheduled_reminder" value="1">
                                Scheduled Reminder
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="escalation_reminder" value="1">
                                Escalation Reminder
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="export_excel" value="1">
                                Export Excel
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="export_pdf" value="1">
                                Export PDF
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="branding" value="1">
                                Branding
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="white_label" value="1">
                                White Label
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="priority_support" value="1">
                                Priority Support
                            </label>

                        </div>

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
                            Create Plan

                        </button>

                        <a href="{{ route('owner.plans.index') }}"
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

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

                <form method="POST" action="{{ route('owner.plans.update', $plan->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- BASIC INFO --}}
                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Plan Name <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="name" value="{{ old('name', $plan->name) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700
                            text-neutral-900 dark:text-neutral-100">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Slug
                            </label>

                            <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Monthly Price
                            </label>

                            <input type="number" step="0.01" name="monthly_price"
                                value="{{ old('monthly_price', $plan->monthly_price) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Yearly Price
                            </label>

                            <input type="number" step="0.01" name="yearly_price"
                                value="{{ old('yearly_price', $plan->yearly_price) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Currency
                            </label>

                            <select name="currency"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">

                                <option value="USD" {{ old('currency', $plan->currency) == 'USD' ? 'selected' : '' }}>
                                    USD
                                </option>

                            </select>
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Sort Order
                            </label>

                            <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Company Users
                            </label>

                            <input type="number" name="company_users"
                                value="{{ old('company_users', $plan->company_users) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Clients
                            </label>

                            <input type="number" name="clients" value="{{ old('clients', $plan->clients) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Document Requests
                            </label>

                            <input type="number" name="document_requests"
                                value="{{ old('document_requests', $plan->document_requests) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Template Limit
                            </label>

                            <input type="number" name="template_limit"
                                value="{{ old('template_limit', $plan->template_limit) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Request Templates
                            </label>

                            <input type="number" name="request_templates"
                                value="{{ old('request_templates', $plan->request_templates) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                WhatsApp Limit
                            </label>

                            <input type="number" name="whatsapp_limit"
                                value="{{ old('whatsapp_limit', $plan->whatsapp_limit) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                Storage (MB)
                            </label>

                            <input type="number" name="storage_mb" value="{{ old('storage_mb', $plan->storage_mb) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>


                        <div>
                            <label class="block text-sm text-neutral-700 dark:text-neutral-300 mb-1">
                                File Size Limit (MB)
                            </label>

                            <input type="number" name="file_size_limit_mb"
                                value="{{ old('file_size_limit_mb', $plan->file_size_limit_mb) }}"
                                class="w-full h-10 px-3 text-sm
                            bg-white dark:bg-neutral-800
                            border border-neutral-300 dark:border-neutral-700">
                        </div>

                    </div>


                    {{-- FEATURES --}}
                    <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-4">
                            Features
                        </h2>

                        <div class="grid md:grid-cols-3 gap-4">

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="client_portal" value="1"
                                    {{ old('client_portal', $plan->client_portal) ? 'checked' : '' }}>
                                Client Portal
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="otp_login" value="1"
                                    {{ old('otp_login', $plan->otp_login) ? 'checked' : '' }}>
                                OTP Login
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="approve_workflow" value="1"
                                    {{ old('approve_workflow', $plan->approve_workflow) ? 'checked' : '' }}>
                                Approve Workflow
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="reupload_history" value="1"
                                    {{ old('reupload_history', $plan->reupload_history) ? 'checked' : '' }}>
                                Reupload History
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="download_zip" value="1"
                                    {{ old('download_zip', $plan->download_zip) ? 'checked' : '' }}>
                                Download ZIP
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="expiry_tracking" value="1"
                                    {{ old('expiry_tracking', $plan->expiry_tracking) ? 'checked' : '' }}>
                                Expiry Tracking
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="renewal_reminder" value="1"
                                    {{ old('renewal_reminder', $plan->renewal_reminder) ? 'checked' : '' }}>
                                Renewal Reminder
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="scheduled_reminder" value="1"
                                    {{ old('scheduled_reminder', $plan->scheduled_reminder) ? 'checked' : '' }}>
                                Scheduled Reminder
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="escalation_reminder" value="1"
                                    {{ old('escalation_reminder', $plan->escalation_reminder) ? 'checked' : '' }}>
                                Escalation Reminder
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="export_excel" value="1"
                                    {{ old('export_excel', $plan->export_excel) ? 'checked' : '' }}>
                                Export Excel
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="export_pdf" value="1"
                                    {{ old('export_pdf', $plan->export_pdf) ? 'checked' : '' }}>
                                Export PDF
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="branding" value="1"
                                    {{ old('branding', $plan->branding) ? 'checked' : '' }}>
                                Branding
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="white_label" value="1"
                                    {{ old('white_label', $plan->white_label) ? 'checked' : '' }}>
                                White Label
                            </label>

                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="priority_support" value="1"
                                    {{ old('priority_support', $plan->priority_support) ? 'checked' : '' }}>
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
                        hover:bg-primary-600 hover:text-white">

                            <x-lucide-save class="w-4 h-4" />
                            Update Plan

                        </button>


                        <a href="{{ route('owner.plans.index') }}"
                            class="w-full sm:w-auto
                        inline-flex items-center justify-center gap-2
                        h-10 px-5 text-sm font-medium
                        border
                        text-neutral-600 border-neutral-300 bg-neutral-100
                        hover:bg-neutral-200">

                            <x-lucide-circle-x class="w-4 h-4" />
                            Cancel

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

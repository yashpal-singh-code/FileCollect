@extends('layouts.app')

@section('title', 'Help')
@section('description', 'Get help and support for FileCollect.')

@section('content')

    <div class="w-full">

        {{-- HEADER --}}
        <div class="max-w-7xl mx-auto mb-6">

            <div class="flex flex-col gap-6 lg:flex-row lg:justify-between lg:items-start">

                <div class="flex-1 min-w-0">

                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Help & Support
                        </h1>

                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Need assistance? Contact our support team.
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- CONTENT --}}
        <div class="max-w-7xl mx-auto">

            <div
                class="border border-neutral-200 dark:border-neutral-800
                    bg-white dark:bg-neutral-900 p-6">

                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">
                    If you are experiencing issues with FileCollect or need assistance with your account,
                    our support team is here to help.
                </p>

                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">
                    Please send us an email with details about your issue. Our team typically responds within
                    <span class="font-medium text-neutral-900 dark:text-neutral-100">24 hours</span>.
                </p>


                {{-- EMAIL --}}
                <div
                    class="border border-neutral-200 dark:border-neutral-800
                        bg-neutral-100 dark:bg-neutral-800
                        px-4 py-3 text-sm">

                    <span class="text-neutral-500 dark:text-neutral-400">
                        Support Email:
                    </span>

                    <a href="mailto:support@filecollect.com"
                        class="ml-2 font-medium text-primary-600 dark:text-primary-400 hover:underline">
                        support@filecollect.com
                    </a>

                </div>


                {{-- HELP INFO --}}
                <div class="mt-6">

                    <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100 mb-3">
                        When contacting support, please include:
                    </p>

                    <ul class="list-disc pl-5 space-y-2 text-sm text-neutral-600 dark:text-neutral-400">
                        <li>Your FileCollect account email</li>
                        <li>A short description of the issue</li>
                        <li>Screenshots or error messages if available</li>
                        <li>The page or feature where the issue occurred</li>
                    </ul>

                </div>

            </div>

        </div>

    </div>

@endsection

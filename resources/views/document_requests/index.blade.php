@extends('layouts.app')

@section('title', 'Document Requests')
@section('description', 'Manage secure document collection from your clients.')

@section('content')

    <div class="w-full">


        {{-- HEADER --}}
        <div class="max-w-7xl mx-auto mb-6">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                {{-- LEFT SIDE --}}
                <div class="space-y-3 min-w-0">

                    {{-- TITLE --}}
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Document Requests
                        </h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Manage secure document collection from your clients.
                        </p>
                    </div>

                </div>

                {{-- RIGHT BUTTON --}}
                <div class="w-full lg:w-auto">

                    @if (is_null($planLimit) || !$limitReached)
                        <a href="{{ route('document-requests.create') }}"
                            class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                        h-10 px-4 sm:px-5 text-sm font-medium border
                        text-primary-600 border-primary-600/30 bg-primary-600/10
                        hover:bg-primary-600 hover:text-white
                        dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                        dark:hover:bg-primary-500 dark:hover:text-white
                        transition cursor-pointer">
                            <x-lucide-plus class="w-4 h-4" />
                            New Request
                        </a>
                    @else
                        <a href="{{ route('pricing') }}"
                            class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                        h-10 px-4 sm:px-5 text-sm font-medium border
                        text-neutral-500 border-neutral-300 bg-neutral-100
                        dark:text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800
                        cursor-not-allowed">
                            <x-lucide-lock class="w-4 h-4" />
                            Limit Reached – Upgrade Plan
                        </a>
                    @endif

                </div>

            </div>

            {{-- LIMIT WARNING --}}
            @if (!is_null($planLimit) && $limitReached)
                <div class="mt-4 p-4 sm:p-5 border border-yellow-500/30 bg-yellow-500/10 rounded-lg">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        <div class="flex items-start gap-2">
                            <x-lucide-alert-triangle class="w-5 h-5 text-yellow-600 dark:text-yellow-400 shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">
                                    You have reached your document request limit.
                                </p>
                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                    Upgrade your plan to send more document requests.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('pricing') }}"
                            class="inline-flex items-center justify-center gap-2
                        h-9 px-4 text-sm font-medium
                        text-yellow-700 dark:text-yellow-300
                        hover:bg-yellow-600 hover:text-white transition">
                            Upgrade Plan →
                        </a>

                    </div>
                </div>
            @endif

        </div>

        {{-- SEARCH --}}
        <div class="max-w-7xl mx-auto">
            <form method="GET"
                class="mb-6 border border-neutral-200 dark:border-neutral-800
                    bg-white dark:bg-neutral-900 p-4">

                <div class="flex flex-col gap-3 lg:grid lg:grid-cols-4 lg:gap-4">

                    {{-- SEARCH INPUT --}}
                    <div class="relative lg:col-span-2">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search request #, first name, last name or email..."
                            class="w-full h-10 pl-10 pr-4 text-sm
                    bg-white dark:bg-neutral-800
                    border border-neutral-300 dark:border-neutral-700
                    text-neutral-900 dark:text-neutral-100
                    focus:outline-none focus:border-primary-500 transition">

                        <x-lucide-search class="w-4 h-4 absolute left-3 top-3 text-neutral-400" />

                    </div>

                    {{-- STATUS FILTER --}}
                    <select name="status"
                        class="h-10 px-3 text-sm bg-white dark:bg-neutral-800
    border border-neutral-300 dark:border-neutral-700
    text-neutral-900 dark:text-neutral-100
    focus:outline-none focus:border-primary-500 transition">

                        <option value="">All Status</option>

                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>
                            Overdue
                        </option>

                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>

                    </select>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex gap-2">

                        <button type="submit"
                            class="flex-1 h-10 inline-flex items-center justify-center gap-2
                    text-sm font-medium border
                    text-primary-600 border-primary-600/30 bg-primary-600/10
                    hover:bg-primary-600 hover:text-white transition cursor-pointer">

                            <x-lucide-filter class="w-4 h-4" />
                            Filter

                        </button>

                        <a href="{{ route('document-requests.index') }}"
                            class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2
                    h-10 px-4 sm:px-5 text-sm font-medium border
                    text-neutral-600 border-neutral-300 bg-neutral-100
                    hover:bg-neutral-200
                    dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
                    dark:hover:bg-neutral-700
                    transition cursor-pointer">

                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            Reset

                        </a>

                    </div>

                </div>
            </form>
        </div>

        {{-- LIST --}}
        <div class="space-y-3 max-w-7xl mx-auto">

            @forelse($documentRequests as $documentRequest)

                <div onclick="window.location='{{ route('document-requests.show', $documentRequest) }}'"
                    class="cursor-pointer
               bg-white dark:bg-neutral-900
               border border-neutral-200 dark:border-neutral-800
               px-4 sm:px-5 py-4
               flex flex-col gap-4
               lg:flex-row lg:items-center lg:justify-between
               hover:shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-800/40
               transition">

                    {{-- LEFT CONTENT --}}
                    <div class="flex-1 min-w-0 space-y-1">

                        <div class="flex items-center gap-2 font-semibold text-neutral-900 dark:text-neutral-100 truncate">
                            <x-lucide-user class="w-4 h-4 text-neutral-400" />
                            {{ $documentRequest->client->full_name ?? 'Unknown Client' }}
                        </div>

                        <div class="flex items-center gap-2 text-xs font-semibold text-primary-600 dark:text-primary-400">
                            <x-lucide-hash class="w-3.5 h-3.5" />
                            Request : {{ $documentRequest->request_number ?? '—' }}
                        </div>

                        {{-- CREATED DATE --}}
                        <div class="flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                            <x-lucide-calendar-days class="w-3.5 h-3.5" />
                            Created: {{ $documentRequest->created_at->format('d M Y h:i A') }}
                        </div>

                        {{-- DUE DATE --}}
                        @if ($documentRequest->expires_at)
                            @if ($documentRequest->expires_at->isPast() && !$documentRequest->isCompleted())
                                <div class="flex items-center gap-2 text-xs text-red-600 dark:text-red-400 font-semibold">
                                    <x-lucide-calendar class="w-3.5 h-3.5" />
                                    Due: {{ $documentRequest->expires_at->format('d M Y h:i A') }} (Overdue)
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                                    <x-lucide-calendar class="w-3.5 h-3.5" />
                                    Due: {{ $documentRequest->expires_at->format('d M Y h:i A') }}
                                </div>
                            @endif
                        @endif

                    </div>

                    {{-- RIGHT SIDE --}}
                    <div class="flex items-center gap-2 flex-wrap">

                        {{-- STATUS --}}
                        <span
                            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold
                {{ $documentRequest->status === 'completed'
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                    : ($documentRequest->status === 'draft'
                        ? 'bg-neutral-200 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300'
                        : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400') }}">

                            @if ($documentRequest->status === 'completed')
                                <x-lucide-check-circle class="w-3.5 h-3.5" />
                            @elseif($documentRequest->status === 'draft')
                                <x-lucide-file-text class="w-3.5 h-3.5" />
                            @else
                                <x-lucide-clock class="w-3.5 h-3.5" />
                            @endif

                            {{ ucfirst($documentRequest->status) }}

                        </span>

                        {{-- VIEW --}}
                        <a href="{{ route('document-requests.show', $documentRequest) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border
                bg-neutral-100 dark:bg-neutral-800
                text-neutral-700 dark:text-neutral-300
                hover:bg-neutral-200 dark:hover:bg-neutral-700
                transition">

                            <x-lucide-eye class="w-3.5 h-3.5" />
                            View
                        </a>

                        {{-- EDIT --}}
                        @if (!$documentRequest->isCompleted())
                            <a href="{{ route('document-requests.edit', $documentRequest) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border
                    text-emerald-600 border-emerald-600/30 bg-emerald-600/10
                    hover:bg-emerald-600 hover:text-white
                    dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10
                    dark:hover:bg-emerald-500 dark:hover:text-white
                    transition">

                                <x-lucide-square-pen class="w-3.5 h-3.5" />
                                Edit
                            </a>
                        @endif

                        {{-- DELETE --}}
                        <button type="button"
                            @click="$dispatch('open-delete', {
                        url: '{{ route('document-requests.destroy', $documentRequest) }}',
                        name: 'Request #{{ $documentRequest->request_number }}'
                    })"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border
                text-red-600 border-red-600/30 bg-red-600/10
                hover:bg-red-600 hover:text-white
                dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
                dark:hover:bg-red-500 dark:hover:text-white
                transition cursor-pointer">

                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                            Delete
                        </button>

                    </div>

                </div>

            @empty

                <div class="text-center py-16 text-neutral-500 dark:text-neutral-400 italic">
                    <x-lucide-inbox class="w-6 h-6 mx-auto mb-2 opacity-40" />
                    No document requests found.
                </div>

            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-6 max-w-7xl mx-auto">
            {{ $documentRequests->links() }}
        </div>

    </div>

    {{-- DOCUMENT REQUEST DELETE MODAL --}}
    <div x-data="{
        show: false,
        url: '',
        name: ''
    }"
        x-on:open-delete.window="
        show = true;
        url = $event.detail.url;
        name = $event.detail.name;
    "
        x-show="show" x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-2 sm:p-4">

        <div @click.away="show=false"
            class="w-full sm:max-w-md
        bg-white dark:bg-neutral-900
        border border-neutral-200 dark:border-neutral-800
        p-5 sm:p-6">

            {{-- Title --}}
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Delete Document Request
            </h2>

            {{-- Description --}}
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                Are you sure you want to delete
                <span class="font-semibold" x-text="name"></span>?
            </p>

            {{-- Warning --}}
            <p class="text-xs mt-2 text-red-600 dark:text-red-400">
                This action cannot be undone.
            </p>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row gap-2 mt-6">

                <button type="button" @click="show=false"
                    class="w-full h-10
                border border-neutral-300 dark:border-neutral-700
                text-sm font-medium
                hover:bg-neutral-100 dark:hover:bg-neutral-800
                transition cursor-pointer">
                    Cancel
                </button>

                <form :action="url" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full h-10
                    text-white text-sm font-medium
                    bg-red-600 hover:bg-red-700
                    transition cursor-pointer">
                        Confirm Delete
                    </button>
                </form>

            </div>

        </div>
    </div>

@endsection

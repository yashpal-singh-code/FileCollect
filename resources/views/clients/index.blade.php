@extends('layouts.app')

@section('title', 'Clients')
@section('description', 'Manage your client accounts.')

@section('content')

    <div class="w-full">

        {{-- HEADER --}}
        <div class="max-w-7xl mx-auto mb-6">

            <div class="flex flex-col gap-6 lg:flex-row lg:justify-between lg:items-start">

                {{-- LEFT SECTION --}}
                <div class="flex-1 min-w-0">

                    {{-- TITLE --}}
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Clients
                        </h1>

                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Manage your client accounts.
                        </p>
                    </div>

                </div>

                {{-- RIGHT BUTTON --}}
                <div class="w-full lg:w-auto lg:ml-6">
                    @can('clients.create')
                        <a href="{{ route('clients.create') }}"
                            class="w-full lg:w-auto
                inline-flex items-center justify-center gap-2
                h-10 px-5 text-sm font-medium border
                text-primary-600 border-primary-600/30 bg-primary-600/10
                hover:bg-primary-600 hover:text-white
                dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                dark:hover:bg-primary-500 dark:hover:text-white
                transition cursor-pointer">

                            <x-lucide-circle-plus class="w-4 h-4" />
                            Add Client

                        </a>
                    @endcan
                </div>

            </div>

        </div>
        {{-- FILTER --}}
        <div class="max-w-7xl mx-auto">
            <form method="GET"
                class="mb-6 border border-neutral-200 dark:border-neutral-800
            bg-white dark:bg-neutral-900 p-4">

                <div class="flex flex-col gap-3 lg:grid lg:grid-cols-4 lg:gap-4">

                    <div class="relative lg:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client..."
                            class="w-full h-10 pl-10 pr-4 text-sm
                        bg-white dark:bg-neutral-800
                        border border-neutral-300 dark:border-neutral-700
                        text-neutral-900 dark:text-neutral-100
                        focus:outline-none focus:border-primary-500 transition">

                        <x-lucide-search class="w-4 h-4 absolute left-3 top-3 text-neutral-400" />
                    </div>

                    <select name="status"
                        class="h-10 px-3 text-sm bg-white dark:bg-neutral-800
                    border border-neutral-300 dark:border-neutral-700
                    text-neutral-900 dark:text-neutral-100
                    focus:outline-none focus:border-primary-500 transition">

                        <option value="">Any Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 h-10 inline-flex items-center justify-center gap-2
                        text-sm font-medium border
                        text-primary-600 border-primary-600/30 bg-primary-600/10
                        hover:bg-primary-600 hover:text-white
                        dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                        dark:hover:bg-primary-500 dark:hover:text-white
                        transition cursor-pointer">
                            <x-lucide-filter class="w-4 h-4" />
                            Filter
                        </button>

                        <a href="{{ route('clients.index') }}"
                            class="flex-1 h-10 inline-flex items-center justify-center gap-2
                        text-sm font-medium border
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

        {{-- BULK FORM --}}
        <form method="POST" action="{{ route('clients.bulk-delete') }}" id="bulk-form">
            @csrf
            @method('DELETE')

            <div id="bulk-bar"
                class="hidden mb-3 px-4 py-2 border
            border-neutral-200 dark:border-neutral-800
            bg-neutral-100 dark:bg-neutral-800
            items-center justify-between">

                <span class="text-sm text-neutral-600 dark:text-neutral-300">
                    <span id="selected-count">0</span> selected
                </span>
                @can('clients.delete')
                    <button type="button" @click="$dispatch('open-client-bulk-delete')"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border
    text-red-600 border-red-600/30 bg-red-600/10
    hover:bg-red-600 hover:text-white
    dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
    dark:hover:bg-red-500 dark:hover:text-white
    transition cursor-pointer">

                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        Delete Selected
                    </button>
                @endcan
            </div>

            {{-- TABLE --}}
            <div class="w-full bg-white dark:bg-neutral-900 overflow-x-auto">

                <table class="w-full text-sm text-left border border-neutral-200 dark:border-neutral-800">

                    <thead class="bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">
                        <tr>

                            <th class="px-4 py-3 w-10 text-center">
                                @can('clients.delete')
                                    <input type="checkbox" id="select-all" class="w-4 h-4 cursor-pointer">
                                @endcan
                            </th>

                            <th class="px-4 py-3 w-14 text-center">#</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Company</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        @forelse($clients as $client)
                            <tr class="hover:bg-neutral-100 dark:hover:bg-neutral-800/40 transition">

                                <td class="px-4 py-3 text-center">
                                    <input type="checkbox" name="selected_clients[]" value="{{ $client->id }}"
                                        class="row-checkbox w-4 h-4 cursor-pointer">
                                </td>

                                <td class="px-4 py-3 text-center text-neutral-500">
                                    {{ $loop->iteration + ($clients->currentPage() - 1) * $clients->perPage() }}
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ $client->full_name }}
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $client->email ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                    {{ $client->phone ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                    {{ $client->company_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 text-xs font-medium border rounded-2xl
                                    {{ $client->status === 'active'
                                        ? 'text-emerald-600 border-emerald-600/30 bg-emerald-600/10 dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10'
                                        : ($client->status === 'inactive'
                                            ? 'text-yellow-600 border-yellow-600/30 bg-yellow-600/10 dark:text-yellow-400 dark:border-yellow-400/40 dark:bg-yellow-400/10'
                                            : 'text-red-600 border-red-600/30 bg-red-600/10 dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10') }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">

                                        {{-- EDIT --}}
                                        @can('clients.edit')
                                            <a href="{{ route('clients.edit', $client) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border
                text-emerald-600 border-emerald-600/30 bg-emerald-600/10
                hover:bg-emerald-600 hover:text-white
                dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10
                dark:hover:bg-emerald-500 dark:hover:text-white
                transition cursor-pointer">
                                                <x-lucide-square-pen class="w-3.5 h-3.5" />
                                                Edit
                                            </a>
                                        @endcan



                                        {{-- DELETE USING MODAL --}}
                                        @can('clients.delete')
                                            <button type="button"
                                                @click="$dispatch('open-client-delete', {
                    url: '{{ route('clients.destroy', $client) }}',
                    name: '{{ $client->full_name }}'
                })"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border
                text-red-600 border-red-600/30 bg-red-600/10
                hover:bg-red-600 hover:text-white
                dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
                dark:hover:bg-red-500 dark:hover:text-white
                transition cursor-pointer">

                                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                Delete
                                            </button>
                                        @endcan

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-4 py-12 text-center text-neutral-500 dark:text-neutral-400 italic">
                                    No clients found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </form>

        <div class="mt-6 max-w-7xl mx-auto px-4 sm:px-0">
            {{ $clients->links() }}
        </div>

    </div>


    {{-- CLIENT DELETE MODAL --}}
    <div x-data="{
        show: false,
        url: '',
        name: ''
    }"
        x-on:open-client-delete.window="
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
                Delete Client
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


    {{-- BULK DELETE MODAL --}}
    <div x-data="{ show: false }" x-on:open-client-bulk-delete.window="show = true" x-show="show" x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-2 sm:p-4">

        <div @click.away="show=false"
            class="w-full sm:max-w-md
        bg-white dark:bg-neutral-900
        border border-neutral-200 dark:border-neutral-800
        p-5 sm:p-6">

            <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Delete Selected Clients
            </h2>

            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                Are you sure you want to delete the selected clients?
            </p>

            <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                This action cannot be undone.
            </p>

            <div class="flex flex-col sm:flex-row gap-2 mt-6">

                <button type="button" @click="show=false"
                    class="w-full h-10
                border border-neutral-300 dark:border-neutral-700
                text-sm font-medium
                hover:bg-neutral-100 dark:hover:bg-neutral-800
                transition cursor-pointer">
                    Cancel
                </button>

                <button type="submit" form="bulk-form"
                    class="w-full h-10
                text-white text-sm font-medium
                bg-red-600 hover:bg-red-700
                transition cursor-pointer">
                    Confirm Delete
                </button>

            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectAll = document.getElementById('select-all');
            const bulkBar = document.getElementById('bulk-bar');
            const countText = document.getElementById('selected-count');

            function getCheckboxes() {
                return document.querySelectorAll('.row-checkbox');
            }

            function getCheckedCount() {
                return document.querySelectorAll('.row-checkbox:checked').length;
            }

            function updateBulkUI() {
                const checkboxes = getCheckboxes();
                const checkedCount = getCheckedCount();

                if (countText) {
                    countText.textContent = checkedCount;
                }

                // Show / Hide bulk bar
                if (bulkBar) {
                    if (checkedCount > 0) {
                        bulkBar.classList.remove('hidden');
                        bulkBar.classList.add('flex');
                    } else {
                        bulkBar.classList.remove('flex');
                        bulkBar.classList.add('hidden');
                    }
                }

                // Update select-all state (indeterminate support)
                if (selectAll) {
                    if (checkedCount === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                    } else if (checkedCount === checkboxes.length) {
                        selectAll.checked = true;
                        selectAll.indeterminate = false;
                    } else {
                        selectAll.indeterminate = true;
                    }
                }
            }

            // Select All Click
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    getCheckboxes().forEach(cb => {
                        cb.checked = selectAll.checked;
                    });
                    updateBulkUI();
                });
            }

            // Individual Checkbox Click (Event Delegation Safe)
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('row-checkbox')) {
                    updateBulkUI();
                }
            });

            // Initialize state on page load
            updateBulkUI();

        });
    </script>
@endsection

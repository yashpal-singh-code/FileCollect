@extends('owner.layouts.app')

@section('title', 'Tenants')
@section('description', 'Manage tenant admin accounts.')

@section('owner_content')

    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="max-w-7xl mx-auto">

            {{-- HEADER --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">

                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                        Tenant Accounts
                    </h1>

                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Manage tenant admin accounts.
                    </p>
                </div>

                <a href="{{ route('owner.users.create') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2
h-10 px-5 text-sm font-medium border
text-primary-600 border-primary-600/30 bg-primary-600/10
hover:bg-primary-600 hover:text-white
dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
dark:hover:bg-primary-500 dark:hover:text-white transition">

                    <x-lucide-circle-plus class="w-4 h-4" />
                    Add Tenant

                </a>

            </div>


            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
                <div
                    class="mb-4 border border-emerald-500/30 bg-emerald-500/10
text-emerald-600 dark:text-emerald-400 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif


            {{-- FILTER --}}
            <form method="GET"
                class="mb-6 border border-neutral-200 dark:border-neutral-800
bg-white dark:bg-neutral-900 p-4">

                <div class="flex flex-col gap-3 md:grid md:grid-cols-4 md:gap-4">

                    <div class="relative md:col-span-2">

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tenant..."
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
text-neutral-900 dark:text-neutral-100">

                        <option value="">Any Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>

                    </select>

                    <div class="flex gap-2">

                        <button type="submit"
                            class="flex-1 h-10 inline-flex items-center justify-center gap-2
text-sm font-medium border
text-primary-600 border-primary-600/30 bg-primary-600/10
hover:bg-primary-600 hover:text-white transition">

                            <x-lucide-filter class="w-4 h-4" />
                            Filter

                        </button>

                        <a href="{{ route('owner.users.index') }}"
                            class="flex-1 h-10 inline-flex items-center justify-center gap-2
text-sm font-medium border
text-neutral-600 border-neutral-300 bg-neutral-100
hover:bg-neutral-200
dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
dark:hover:bg-neutral-700 transition">

                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            Reset

                        </a>

                    </div>

                </div>

            </form>


            {{-- BULK FORM --}}
            <form method="POST" action="{{ route('owner.users.bulk-delete') }}" id="bulk-form">
                @csrf
                @method('DELETE')

                {{-- TABLE --}}
                <div class="overflow-x-auto border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">

                    <table class="min-w-full text-sm">

                        <thead class="bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300">

                            <tr>

                                <th class="px-4 py-3 w-14 text-center font-medium">
                                    #
                                </th>

                                <th class="px-4 py-3 text-left font-medium min-w-[220px]">
                                    Tenant
                                </th>
                                <th class="px-4 py-3 text-left font-medium min-w-[200px]">
                                    Company
                                </th>

                                <th class="px-4 py-3 text-left font-medium min-w-[120px]">
                                    Status
                                </th>

                                <th class="px-4 py-3 text-right font-medium min-w-[240px]">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">

                            @forelse($users as $user)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40">

                                    <td class="px-4 py-3 text-center text-neutral-500">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>

                                    <td class="px-4 py-3">

                                        <div class="flex flex-col">
                                            <p class="font-medium text-neutral-900 dark:text-neutral-100">
                                                {{ $user->name }}
                                            </p>

                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $user->email }}
                                            </p>
                                        </div>

                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        {{ $user->companySetting->company_name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">

                                        @if ($user->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 text-xs border rounded-full
                            text-emerald-600 border-emerald-600/30 bg-emerald-600/10">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 text-xs border rounded-full
                            text-red-600 border-red-600/30 bg-red-600/10">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-4 py-3 text-right">

                                        <div class="flex items-center justify-end gap-2">

                                            <a href="{{ route('owner.users.show', $user) }}"
                                                class="inline-flex items-center gap-1.5
                                h-8 px-3 text-xs font-medium border
                                text-blue-600 border-blue-600/30 bg-blue-600/10
                                hover:bg-blue-600 hover:text-white transition">

                                                <x-lucide-eye class="w-3.5 h-3.5" />
                                                View

                                            </a>

                                            <a href="{{ route('owner.users.edit', $user) }}"
                                                class="inline-flex items-center gap-1.5
                                h-8 px-3 text-xs font-medium border
                                text-emerald-600 border-emerald-600/30 bg-emerald-600/10
                                hover:bg-emerald-600 hover:text-white transition">

                                                <x-lucide-square-pen class="w-3.5 h-3.5" />
                                                Edit

                                            </a>
                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6"
                                        class="px-4 py-12 text-center text-neutral-500 dark:text-neutral-400 italic">
                                        No tenants found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </form>


            {{-- PAGINATION --}}
            <div class="mt-6">
                {{ $users->links() }}
            </div>

        </div>
    </div>


    {{-- TENANT DELETE MODAL --}}
    <div x-data="{ show: false, url: '', name: '' }" x-on:open-user-delete.window="
show=true;
url=$event.detail.url;
name=$event.detail.name;
"
        x-show="show" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">

        <div @click.away="show=false"
            class="w-full sm:max-w-md bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-6">

            <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Delete Tenant
            </h2>

            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                Are you sure you want to delete
                <span class="font-semibold" x-text="name"></span>?
            </p>

            <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                This action cannot be undone.
            </p>

            <div class="flex flex-col sm:flex-row gap-2 mt-6">

                <button type="button" @click="show=false"
                    class="w-full h-10 border border-neutral-300 dark:border-neutral-700 text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    Cancel
                </button>

                <form :action="url" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="w-full h-10 text-white text-sm font-medium bg-red-600 hover:bg-red-700">
                        Confirm Delete
                    </button>

                </form>

            </div>

        </div>
    </div>


    {{-- BULK DELETE MODAL --}}
    <div x-data="{ show: false }" x-on:open-user-bulk-delete.window="show=true" x-show="show" x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-4">

        <div @click.away="show=false"
            class="w-full sm:max-w-md bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-6">

            <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Delete Selected Tenants
            </h2>

            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                Are you sure you want to delete the selected tenants?
            </p>

            <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                This action cannot be undone.
            </p>

            <div class="flex flex-col sm:flex-row gap-2 mt-6">

                <button type="button" @click="show=false"
                    class="w-full h-10 border border-neutral-300 dark:border-neutral-700 text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800">
                    Cancel
                </button>

                <button type="submit" form="bulk-form"
                    class="w-full h-10 text-white text-sm font-medium bg-red-600 hover:bg-red-700">
                    Confirm Delete
                </button>

            </div>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBar = document.getElementById('bulk-bar');
            const count = document.getElementById('selected-count');

            function update() {

                let checked = document.querySelectorAll('.row-checkbox:checked').length;
                count.textContent = checked;

                if (checked > 0) {
                    bulkBar.classList.remove('hidden');
                    bulkBar.classList.add('flex');
                } else {
                    bulkBar.classList.remove('flex');
                    bulkBar.classList.add('hidden');
                }

            }

            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                update();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', update);
            });

        });
    </script>

@endsection

@extends('layouts.app')

@section('title', 'Users')
@section('description', 'Manage your team members.')

@section('content')

    <div class="w-full">

        {{-- HEADER --}}
        <div class="max-w-7xl mx-auto mb-6">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div class="space-y-3 min-w-0">

                    <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                        Users
                    </h1>

                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Manage your team members.
                    </p>

                </div>

                <div class="w-full lg:w-auto">

                    @if (is_null($planLimit) || $remainingUsers > 0)
                        <a href="{{ route('users.create') }}"
                            class="w-full lg:w-auto
inline-flex items-center justify-center gap-2
h-10 px-4 sm:px-5 text-sm font-medium border
text-primary-600 border-primary-600/30 bg-primary-600/10
hover:bg-primary-600 hover:text-white
dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
dark:hover:bg-primary-500 dark:hover:text-white
transition cursor-pointer">

                            <x-lucide-circle-plus class="w-4 h-4" />
                            Add User

                        </a>
                    @else
                        <a href="{{ route('pricing') }}"
                            class="w-full lg:w-auto
inline-flex items-center justify-center gap-2
h-10 px-4 sm:px-5 text-sm font-medium border
text-neutral-500 border-neutral-300 bg-neutral-100
dark:text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800
cursor-not-allowed">

                            <x-lucide-lock class="w-4 h-4" />
                            Limit Reached Update Your Plan

                        </a>
                    @endif

                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="max-w-7xl mx-auto">

            <form method="GET"
                class="mb-6 border border-neutral-200 dark:border-neutral-800
bg-white dark:bg-neutral-900 p-4">

                <div class="flex flex-col gap-3 lg:grid lg:grid-cols-5 lg:gap-4">

                    <div class="relative lg:col-span-2">

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user..."
                            class="w-full h-10 pl-10 pr-4 text-sm
bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition">

                        <x-lucide-search class="w-4 h-4 absolute left-3 top-3 text-neutral-400 dark:text-neutral-500" />

                    </div>

                    <select name="role"
                        class="h-10 px-3 text-sm bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition cursor-pointer">

                        <option value="">All Roles</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach

                    </select>

                    <select name="status"
                        class="h-10 px-3 text-sm bg-white dark:bg-neutral-800
border border-neutral-300 dark:border-neutral-700
text-neutral-900 dark:text-neutral-100
focus:outline-none focus:border-primary-500 transition cursor-pointer">

                        <option value="">Any Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>

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

                        <a href="{{ route('users.index') }}"
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
        <form method="POST" action="{{ route('users.bulk-delete') }}" id="bulk-form">
            @csrf
            @method('DELETE')
        </form>

        {{-- BULK BAR --}}
        <div id="bulk-bar"
            class="hidden mb-3 px-4 py-2 border
border-neutral-200 dark:border-neutral-800
bg-neutral-100 dark:bg-neutral-800
items-center justify-between">

            <span class="text-sm text-neutral-600 dark:text-neutral-300">
                <span id="selected-count">0</span> selected
            </span>

            <button type="button" x-on:click="$dispatch('open-user-bulk-delete')"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border
text-red-600 border-red-600/30 bg-red-600/10
hover:bg-red-600 hover:text-white
dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
dark:hover:bg-red-500 dark:hover:text-white
transition cursor-pointer">

                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                Delete Selected

            </button>

        </div>

        {{-- TABLE --}}
        <div class="w-full bg-white dark:bg-neutral-900 overflow-x-auto">

            <table class="w-full text-sm text-left min-w-250 border border-neutral-200 dark:border-neutral-800">

                <thead class="bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200">

                    <tr>

                        <th class="px-4 py-3 w-10 text-center">
                            <input type="checkbox" id="select-all" class="w-4 h-4 cursor-pointer">
                        </th>

                        <th class="px-4 py-3 w-14 text-center">#</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Job Title</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">

                    @forelse($users as $user)
                        <tr class="hover:bg-neutral-100 dark:hover:bg-neutral-800/40 transition">

                            <td class="px-4 py-3 text-center">

                                @if (!$user->is_owner && !$user->hasRole('super_admin') && $user->id !== auth()->id())
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                        form="bulk-form" class="row-checkbox w-4 h-4 cursor-pointer">
                                @endif

                            </td>

                            <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400 text-center">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center bg-primary-600 text-white text-xs font-bold">
                                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                    </div>

                                    <div>

                                        <p class="font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </p>

                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ $user->email }}
                                        </p>

                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-2xl">
                                    {{ ucwords(str_replace('_', ' ', $user->roles->first()?->name ?? 'user')) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                {{ $user->phone ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">
                                {{ $user->job_title ?? '-' }}
                            </td>

                            <td class="px-4 py-3">

                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium border rounded-2xl
{{ $user->is_active
    ? 'text-emerald-600 border-emerald-600/30 bg-emerald-600/10 dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10'
    : 'text-red-600 border-red-600/30 bg-red-600/10 dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10' }}">

                                    {{ $user->is_active ? 'Active' : 'Inactive' }}

                                </span>

                            </td>

                            <td class="px-4 py-3 text-right">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('users.edit', $user) }}"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border
text-emerald-600 border-emerald-600/30 bg-emerald-600/10
hover:bg-emerald-600 hover:text-white
dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10
dark:hover:bg-emerald-500 dark:hover:text-white
transition cursor-pointer">

                                        <x-lucide-square-pen class="w-3.5 h-3.5" />
                                        Edit

                                    </a>

                                    @if (!$user->is_owner && !$user->hasRole('super_admin') && $user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                            class="inline" onsubmit="return confirm('Delete this user?')">

                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                x-on:click="$dispatch('open-user-delete', {
    url: '{{ route('users.destroy', $user->id) }}',
    name: '{{ $user->first_name }} {{ $user->last_name }}'
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

                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-neutral-500 dark:text-neutral-400 italic">
                                No team members found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>

        <div class="mt-6 max-w-7xl mx-auto px-4 sm:px-0">
            {{ $users->links() }}
        </div>

    </div>


    {{-- USER DELETE MODAL --}}
    <div x-data="{
        show: false,
        url: '',
        name: ''
    }"
        x-on:open-user-delete.window="
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
                Delete User
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



    {{-- BULK USER DELETE MODAL --}}
    <div x-data="{ show: false }" x-on:open-user-bulk-delete.window="show = true" x-show="show" x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-2 sm:p-4">

        <div @click.away="show=false"
            class="w-full sm:max-w-md
bg-white dark:bg-neutral-900
border border-neutral-200 dark:border-neutral-800
p-5 sm:p-6">

            <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                Delete Selected Users
            </h2>

            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                Are you sure you want to delete the selected users?
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
            const checkboxes = document.querySelectorAll('.row-checkbox');

            function updateBulkUI() {

                const checked = document.querySelectorAll('.row-checkbox:checked').length;

                countText.textContent = checked;

                if (checked > 0) {

                    bulkBar.classList.remove('hidden');
                    bulkBar.classList.add('flex');

                } else {

                    bulkBar.classList.remove('flex');
                    bulkBar.classList.add('hidden');

                }

            }

            if (selectAll) {

                selectAll.addEventListener('change', function() {

                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkUI();

                });

            }

            checkboxes.forEach(cb => {

                cb.addEventListener('change', updateBulkUI);

            });

        });
    </script>

@endsection

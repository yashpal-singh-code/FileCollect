@extends('layouts.app')

@section('title', 'Role Permission Management')
@section('description', 'Assign permissions to roles.')

@section('content')

    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
            <div class="flex items-center gap-3">
                <x-lucide-user-round-key class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                        Role Permission Management
                    </h1>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                        Assign permissions to system roles
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">

            {{-- ROLE SELECT --}}
            <form method="GET" class="mb-6">
                <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                    Select Role
                </label>

                <select name="role_id" onchange="this.form.submit()"
                    class="w-full md:w-64 h-9 px-3 text-sm bg-white dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100
                             border border-neutral-300 dark:border-neutral-700
                             focus:outline-none focus:border-primary-500 transition">

                    <option value="">Select Role</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach

                </select>
            </form>

            @if ($selectedRole)

                <form method="POST" action="{{ route('roles.manage.update') }}" x-data="permissionMatrix()">

                    @csrf
                    <input type="hidden" name="role_id" value="{{ $selectedRole->id }}">

                    {{-- GLOBAL SELECT ALL --}}
                    <div class="mb-4 flex items-center gap-3">
                        <input type="checkbox" class="rounded border-neutral-300 dark:border-neutral-600 text-primary-600"
                            @click="toggleAll($event)">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Select All Permissions
                        </span>
                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-neutral-200 dark:border-neutral-800">

                            <thead class="bg-neutral-50 dark:bg-neutral-800">
                                <tr>
                                    <th class="p-3 text-left border border-neutral-200 dark:border-neutral-700">
                                        Module
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        All
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        View
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        Create
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        Edit
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        Delete
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        Send
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        Manage
                                    </th>

                                    <th class="p-3 text-center border border-neutral-200 dark:border-neutral-700">
                                        Assign
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $module => $modulePermissions)
                                    <tr
                                        class="border-t border-neutral-200 dark:border-neutral-800
                                         hover:bg-neutral-50 dark:hover:bg-neutral-800/60 transition">

                                        <td
                                            class="p-3 border border-neutral-200 dark:border-neutral-800 font-medium capitalize text-neutral-800 dark:text-neutral-200">
                                            {{ str_replace('_', ' ', $module) }}
                                        </td>

                                        {{-- MODULE SELECT ALL (BEFORE VIEW) --}}
                                        <td class="p-3 text-center border border-neutral-200 dark:border-neutral-800">
                                            <input type="checkbox"
                                                class="rounded border-neutral-300 dark:border-neutral-600 text-primary-600"
                                                @click="toggleModule('{{ $module }}', $event)">
                                        </td>

                                        @foreach (['view', 'create', 'edit', 'delete', 'send', 'manage', 'assign_permissions'] as $action)
                                            @php $permissionName = $module.'.'.$action; @endphp

                                            <td class="p-3 text-center border border-neutral-200 dark:border-neutral-800">

                                                @if ($modulePermissions->contains('name', $permissionName))
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permissionName }}"
                                                        class="module-{{ $module }} rounded border-neutral-300 dark:border-neutral-600 text-primary-600"
                                                        {{ $selectedRole->permissions->contains('name', $permissionName) ? 'checked' : '' }}>
                                                @endif

                                            </td>
                                        @endforeach

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{-- SAVE BUTTON --}}
                    <div class="pt-6">
                        <button type="submit"
                            class="h-9 px-6 inline-flex items-center gap-2 text-sm font-semibold
                              border text-primary-600 border-primary-600/30 bg-primary-600/10
                              hover:bg-primary-600 hover:text-white
                              dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                              dark:hover:bg-primary-500 dark:hover:text-white transition">
                            <x-lucide-save class="w-4 h-4" />
                            Save Permissions
                        </button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    {{-- ALPINE --}}
    <script>
        function permissionMatrix() {
            return {

                toggleAll(event) {
                    document.querySelectorAll('input[name="permissions[]"]')
                        .forEach(cb => cb.checked = event.target.checked);
                },

                toggleModule(module, event) {
                    document.querySelectorAll('.module-' + module)
                        .forEach(cb => cb.checked = event.target.checked);
                }

            }
        }
    </script>

@endsection

<div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">

    {{-- Overlay --}}
    <div @click="deleteOpen = false" class="absolute inset-0 bg-black/50"></div>

    {{-- Modal --}}
    <div
        class="relative w-full max-w-md
               mx-4 sm:mx-0
               bg-white dark:bg-gray-900
               border border-gray-200 dark:border-gray-800
               p-6">

        {{-- Title --}}
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
            Delete User
        </h2>

        {{-- Message --}}
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            Are you sure you want to delete
            <span class="font-semibold text-gray-900 dark:text-gray-100"
                x-text="`${selectedUser.first_name} ${selectedUser.last_name}`"></span>?
            <span class="block mt-1 text-red-600 dark:text-red-400">
                This action cannot be undone.
            </span>
        </p>

        {{-- Actions --}}
        <form :action="`/tenant/{{ request()->route('tenant') }}/users/${selectedUser.id}`" method="POST"
            class="flex justify-end gap-3">
            @csrf
            @method('DELETE')

            {{-- Cancel (secondary) --}}
            <button type="button" @click="deleteOpen = false"
                class="h-9 px-4
           border border-gray-300 dark:border-gray-700
           bg-white dark:bg-gray-800
           text-gray-700 dark:text-gray-300
           text-sm font-medium
           hover:bg-gray-50 dark:hover:bg-gray-700
           transition
           cursor-pointer">
                Cancel
            </button>

            {{-- Delete (destructive) --}}
            <button type="submit"
                class="h-9 px-4
           bg-red-600 hover:bg-red-700
           dark:bg-red-500 dark:hover:bg-red-600
           text-white text-sm font-medium
           transition
           cursor-pointer">
                Delete
            </button>

        </form>

    </div>
</div>

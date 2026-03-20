<!-- Global Toast (Top Center) -->
<div x-data="{
    show: @json($errors->any() || session('success') || session('error')),
    message: '',
    type: '',
    timeout: null,

    init() {
        @if ($errors->any()) this.message = '{{ $errors->first() }}'; this.type = 'error'; @endif
        @if (session('error')) this.message = '{{ session('error') }}'; this.type = 'error'; @endif
        @if (session('success')) this.message = '{{ session('success') }}'; this.type = 'success'; @endif

        if (this.show) this.startTimer()
    },

    startTimer() {
        this.timeout = setTimeout(() => this.show = false, 4000)
    },

    close() {
        clearTimeout(this.timeout)
        this.show = false
    }
}" x-show="show" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-3" x-cloak
    class="fixed top-5 left-1/2 -translate-x-1/2 z-9999 w-full max-w-sm px-4">

    <div :class="type === 'error'
        ?
        'border-red-200 bg-white' :
        'border-emerald-200 bg-white'"
        class="border shadow-sm">

        <!-- Top Accent Line -->
        <div :class="type === 'error'
            ?
            'bg-red-600' :
            'bg-emerald-600'"
            class="h-0.5 w-full">
        </div>

        <div class="px-4 py-3 flex items-start gap-3">

            <!-- ICON -->
            <div>
                <template x-if="type === 'error'">
                    <div class="w-5 h-5 text-red-600">
                        <x-lucide-alert-circle class="w-5 h-5" />
                    </div>
                </template>

                <template x-if="type === 'success'">
                    <div class="w-5 h-5 text-emerald-600">
                        <x-lucide-check-circle class="w-5 h-5" />
                    </div>
                </template>
            </div>

            <!-- MESSAGE -->
            <div class="flex-1">
                <p :class="type === 'error'
                    ?
                    'text-red-700' :
                    'text-emerald-700'"
                    class="text-sm font-medium leading-snug" x-text="message">
                </p>
            </div>

            <!-- CLOSE -->
            <button @click="close()" class="text-gray-400 hover:text-gray-600 transition">
                <x-lucide-x class="w-4 h-4" />
            </button>

        </div>

    </div>

</div>

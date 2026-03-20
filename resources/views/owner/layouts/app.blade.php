<!DOCTYPE html>
<html lang="en" class="h-full antialiased">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>
        @hasSection('title')
            @yield('title') | FileCollect
        @else
            FileCollect – Collect, Track & Complete Client Documents Faster
        @endif
    </title>

    <meta name="description" content="@yield('description', 'FileCollect is a secure client document collection platform for agencies and businesses.')">

    <meta name="keywords" content="@yield('keywords', 'file collection software, client document collection, secure upload')">

    <meta name="author" content="FileCollect">
    <meta name="robots" content="index, follow">

    {{-- Theme Color --}}
    <meta name="theme-color" content="#2563eb">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="FileCollect">

    <meta property="og:title"
        content="@hasSection('title')
@yield('title') | FileCollect
@else
FileCollect – Secure Client Document Collection
@endif">

    <meta property="og:description" content="@yield('description', 'Securely collect, track, and manage client documents with FileCollect.')">

    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Use your real logo for social preview --}}
    <meta property="og:image" content="{{ asset('img/logo.png') }}">

    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:title"
        content="@hasSection('title')
@yield('title') | FileCollect
@else
FileCollect
@endif">

    <meta name="twitter:description" content="@yield('description', 'Secure client document collection platform.')">

    <meta name="twitter:image" content="{{ asset('img/logo.png') }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/fabicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/fabicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/fabicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/fabicon.png') }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <script>
        (() => {
            const theme = localStorage.getItem("theme") ?? "system";
            const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

            if (theme === "dark" || (theme === "system" && prefersDark)) {
                document.documentElement.classList.add("dark");
            }
        })();
    </script>

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
    class="h-full bg-neutral-50 dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100 transition-colors duration-300"
    x-data="{
        sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen')) ?? false,
        openMenu: localStorage.getItem('openMenu') ?? 'none',
        active: localStorage.getItem('activeMenu') ?? 'dashboard',
        darkMode: localStorage.getItem('theme') ?? 'system',
    
        setActive(val, parent = 'none') {
            this.active = val;
            this.openMenu = parent;
            localStorage.setItem('activeMenu', val);
            localStorage.setItem('openMenu', parent);
            if (window.innerWidth < 768) this.sidebarOpen = false;
        },
    
        applyTheme(mode) {
            localStorage.setItem('theme', mode);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = mode === 'dark' || (mode === 'system' && prefersDark);
            document.documentElement.classList.toggle('dark', isDark);
        }
    }" x-init="$watch('darkMode', v => applyTheme(v));
    $watch('sidebarOpen', v => localStorage.setItem('sidebarOpen', v));
    $watch('openMenu', v => localStorage.setItem('openMenu', v));
    $watch('active', v => localStorage.setItem('activeMenu', v));"
    @keydown.escape.window="
    openMenu = 'none';
    sidebarOpen = false;
">

    <!-- Mobile Header -->
    <header
        class="md:hidden flex items-center justify-between px-4 h-16
    bg-white dark:bg-neutral-800
    border-b border-neutral-200 dark:border-neutral-700
    sticky top-0 z-40">

        {{-- Logo --}}
        <div class="flex items-center gap-2">
            <img src="{{ asset('img/logo.png') }}" alt="FileCollect Logo" class="h-18 w-auto">
        </div>

        {{-- Sidebar Toggle --}}
        <button @click="sidebarOpen = true"
            class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 transition">
            ☰
        </button>

    </header>

    <div class="flex h-full">

        <!-- Overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/40 dark:bg-black/60 z-40 md:hidden">
        </div>

        @include('owner.layouts.sidebar')

        <main class="flex-1 px-4 md:px-6 py-4 overflow-y-auto">
            @yield('owner_content')
        </main>

    </div>


    <!-- Global Toast (Top Center) -->
    <div x-data="{
        show: @json($errors->any() || session('success') || session('error')),
        message: '',
        type: '',
        timeout: null,
    
        init() {
    
            @if ($errors->any()) this.message = '{{ $errors->first() }}'
            this.type = 'error' @endif
    
            @if (session('error')) this.message = '{{ session('error') }}'
            this.type = 'error' @endif
    
            @if (session('success')) this.message = '{{ session('success') }}'
            this.type = 'success' @endif
    
            if (this.show) {
                this.startTimer()
            }
        },
    
        startTimer() {
            this.timeout = setTimeout(() => this.show = false, 4000)
        },
    
        close() {
            clearTimeout(this.timeout)
            this.show = false
        }
    }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4" x-cloak
        class="fixed top-6 left-1/2 -translate-x-1/2 z-9999 w-full max-w-sm">

        <div :class="type === 'error'
            ?
            'border-red-300 bg-red-50 dark:bg-red-900/30 dark:border-red-700' :
            'border-emerald-300 bg-emerald-50 dark:bg-emerald-900/30 dark:border-emerald-700'"
            class="border shadow-lg px-4 py-4 rounded-lg">

            <div class="flex items-start gap-3">

                <!-- ICON -->
                <div>
                    <template x-if="type === 'error'">
                        <div class="w-6 h-6 flex items-center justify-center text-red-600 dark:text-red-400">
                            <x-lucide-alert-circle class="w-5 h-5" />
                        </div>
                    </template>

                    <template x-if="type === 'success'">
                        <div class="w-6 h-6 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <x-lucide-check-circle class="w-5 h-5" />
                        </div>
                    </template>
                </div>

                <!-- MESSAGE -->
                <div class="flex-1">
                    <p :class="type === 'error'
                        ?
                        'text-red-800 dark:text-red-200' :
                        'text-emerald-800 dark:text-emerald-200'"
                        class="text-sm font-medium" x-text="message">
                    </p>
                </div>

                <!-- CLOSE -->
                <button @click="close()"
                    class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition">
                    <x-lucide-x class="w-4 h-4" />
                </button>

            </div>

        </div>

    </div>
</body>

</html>

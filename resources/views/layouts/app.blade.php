<!DOCTYPE html>
<html lang="en" class="h-full antialiased">

<head>
    @include('layouts.head')
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
            <img src="{{ asset('img/logo.svg') }}" alt="FileCollect Logo" class="h-10 w-auto">
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

        @include('layouts.sidebar')

        <main class="flex-1 px-4 md:px-6 py-4 overflow-y-auto">
            @yield('content')
        </main>

    </div>


    <!-- Global Toast (Top Center) -->
    @include('layouts.toaster')
</body>

</html>

<aside x-init="requestAnimationFrame(() => $el.classList.add('transition-transform'))"
    class="fixed inset-y-0 left-0 z-50 w-64
           bg-white dark:bg-neutral-900
           border-r border-neutral-200 dark:border-neutral-800
           transform duration-300 md:relative md:translate-x-0
           flex flex-col"
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>

    <!-- Brand -->
    <div class="h-16 flex items-center px-6
            border-b border-neutral-200 dark:border-neutral-800">

        <a href="{{ route('owner.dashboard') }}" class="flex items-center">
            <img src="{{ asset('img/logo.png') }}" alt="FileCollect Logo" class="max-h-16 w-auto object-contain">
        </a>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" @click="sidebarOpen = false">

        <!-- Dashboard -->
        <a href="{{ route('owner.dashboard') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('owner.dashboard')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
            <x-lucide-layout-dashboard class="w-5 h-5" />
            Dashboard
        </a>

        <!-- document-requests -->
        <a href="{{ route('owner.plans.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('owner.plans.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
            <x-lucide-file-text class="w-5 h-5" />
            Plan Manage
        </a>

        <!-- document-requests -->
        <a href="{{ route('owner.users.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('owner.users.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
            <x-lucide-users class="w-5 h-5" />
            All Tenants
        </a>

    </nav>



    <!-- Profile Footer -->
    <div class="relative p-4
                border-t border-neutral-200 dark:border-neutral-800"
        x-data="{ open: false, themeOpen: false }" @keydown.escape.window="open = false; themeOpen = false">

        <!-- Dropdown -->
        <div x-show="open" x-transition x-cloak @click.outside="open = false; themeOpen = false"
            class="absolute bottom-20 inset-x-4
                   bg-white dark:bg-neutral-900
                   border border-neutral-200 dark:border-neutral-800
                   rounded-xl shadow-xl z-50">

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm
                      text-neutral-700 dark:text-neutral-300
                      hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                <x-lucide-user-circle class="w-4 h-4 shrink-0" />
                My Profile
            </a>

            <!-- Theme Menu -->
            <div class="space-y-0.5" x-data="{ themeOpen: false }">

                <!-- Main Button -->
                <button type="button" @click="themeOpen = !themeOpen"
                    class="w-full flex items-center justify-between px-4 py-2 rounded-lg transition-colors
        text-neutral-600 dark:text-neutral-400
        hover:bg-neutral-100 dark:hover:bg-neutral-800
        cursor-pointer">

                    <div class="flex items-center gap-2">
                        <x-lucide-palette class="w-5 h-5" />
                        Theme
                    </div>

                    <x-lucide-chevron-down
                        class="w-4 h-4 transition-transform duration-300 text-neutral-400 dark:text-neutral-500"
                        x-bind:class="themeOpen ? 'rotate-180' : ''" />
                </button>

                <!-- Submenu -->
                <div x-show="themeOpen" x-collapse x-cloak
                    class="ml-6 mt-1 space-y-0.5 border-l-2 border-neutral-200 dark:border-neutral-700 pl-3">

                    <!-- Light -->
                    <button type="button" @click="darkMode='light'; themeOpen=false; open=false"
                        class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm transition-colors
            hover:bg-neutral-100 dark:hover:bg-neutral-800
            cursor-pointer"
                        :class="darkMode === 'light'
                            ?
                            'bg-primary-50 text-primary-700 font-semibold dark:bg-neutral-800 dark:text-white' :
                            'text-neutral-600 dark:text-neutral-400'">

                        <x-lucide-sun class="w-4 h-4" />
                        Light
                    </button>

                    <!-- Dark -->
                    <button type="button" @click="darkMode='dark'; themeOpen=false; open=false"
                        class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm transition-colors
            hover:bg-neutral-100 dark:hover:bg-neutral-800
            cursor-pointer"
                        :class="darkMode === 'dark'
                            ?
                            'bg-primary-50 text-primary-700 font-semibold dark:bg-neutral-800 dark:text-white' :
                            'text-neutral-600 dark:text-neutral-400'">

                        <x-lucide-moon class="w-4 h-4" />
                        Dark
                    </button>

                    <!-- System -->
                    <button type="button" @click="darkMode='system'; themeOpen=false; open=false"
                        class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm transition-colors
            hover:bg-neutral-100 dark:hover:bg-neutral-800
            cursor-pointer"
                        :class="darkMode === 'system'
                            ?
                            'bg-primary-50 text-primary-700 font-semibold dark:bg-neutral-800 dark:text-white' :
                            'text-neutral-600 dark:text-neutral-400'">

                        <x-lucide-monitor class="w-4 h-4" />
                        System
                    </button>

                </div>
            </div>

            <!-- Support -->
            <a href="{{ route('support.index') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm
                      text-neutral-700 dark:text-neutral-300
                      hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
                <x-lucide-headset class="w-4 h-4" />
                Support
            </a>


            <div class="h-px bg-neutral-200 dark:bg-neutral-800 my-2"></div>

            <!-- Logout -->
            <form method="POST" action="/logout">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-3 text-sm font-semibold
                           text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition cursor-pointer">
                    <x-lucide-log-out class="w-4 h-4 shrink-0" />
                    Logout
                </button>
            </form>

        </div>

        <!-- Trigger -->
        <button type="button" @click="open = !open"
            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg
           hover:bg-neutral-100 dark:hover:bg-neutral-800 transition cursor-pointer">

            @if (auth()->user()->avatar && file_exists(public_path('storage/' . auth()->user()->avatar)))
                <img class="w-9 h-9 rounded-full border border-neutral-200 dark:border-neutral-700 shrink-0 object-cover"
                    src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @else
                <div
                    class="w-9 h-9 rounded-full flex items-center justify-center
           bg-primary-500 dark:bg-primary-400
           text-white text-sm font-semibold
           border border-neutral-200 dark:border-neutral-700 shrink-0">
                    {{ auth()->user()->initials }}
                </div>
            @endif

            <div class="min-w-0 text-left">
                <p class="text-sm font-semibold truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-[11px] text-neutral-500 truncate">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <x-lucide-chevrons-up-down class="w-4 h-4 ml-auto text-neutral-400 transition-transform"
                x-bind:class="open ? 'rotate-180' : ''" />
        </button>

    </div>
</aside>

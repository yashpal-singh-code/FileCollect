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

        <a href="{{ route('dashboard') }}" class="flex items-center">

            <!-- Icon -->
            <div class="flex items-center justify-center w-11 h-11">
                {{-- <img src="{{ asset('img/logo.svg') }}"> --}}
                <svg viewBox="0 0 24 24" class="h-10 w-10 text-blue-600" fill="none">
                    <path d="M6 5h8M6 5v14M6 11h6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" />

                    <path d="M18 7a5 5 0 100 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>

            <!-- Brand -->
            <div class="flex flex-col">
                <span class="block text-xl font-semibold text-blue-600 leading-tight">
                    FileCollect
                </span>

                <span class="block text-xs text-blue-600 leading-tight">
                    Secure • Organize
                </span>
            </div>

        </a>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" @click="sidebarOpen = false">

        <!-- Dashboard -->
        @can('dashboard.view')
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('dashboard')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                <x-lucide-layout-dashboard class="w-5 h-5" />
                Dashboard
            </a>
        @endcan

        <!-- document-requests -->
        @can('document_requests.view')
            <a href="{{ route('document-requests.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('document-requests.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                <x-lucide-file-text class="w-5 h-5" />
                Document Request
            </a>
        @endcan

        <!-- Clients -->
        @can('clients.view')
            <a href="{{ route('clients.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('clients.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                <x-lucide-contact-round class="w-5 h-5" />
                Clients
            </a>
        @endcan

        <!-- Templates -->
        @can('templates.view')
            <a href="{{ route('templates.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('templates.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                <x-lucide-layout-template class="w-5 h-5" />
                Templates
            </a>
        @endcan
        <!-- Users -->
        @can('teams.view')
            <a href="{{ route('users.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('users.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                <x-lucide-users class="w-5 h-5" />
                Teams
            </a>
        @endcan

        <!-- document-requests -->
        @can('subscriptions.view')
            <a href="{{ route('subscriptions.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('subscriptions.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                <x-lucide-wallet class="w-5 h-5" />
                Subscriptions
            </a>
        @endcan


        <!-- Administration Settings -->
        @canany(['company_settings.view', 'roles.view'])
            <div class="space-y-0.5" x-data="{ openMenu: {{ request()->routeIs('company-settings.*', 'roles.manage', 'settings.2mfa') ? 'true' : 'false' }} }">

                <!-- Main Button -->
                <button type="button" @click="openMenu = !openMenu"
                    class="w-full flex items-center justify-between px-4 py-2 rounded-lg transition-colors
          text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800">

                    <div class="flex items-center gap-2">
                        <x-lucide-settings class="w-5 h-5" />
                        Administration
                    </div>

                    <x-lucide-chevron-down
                        class="w-5 h-5 transition-transform duration-300 text-neutral-400 dark:text-neutral-500"
                        x-bind:class="openMenu ? 'rotate-180' : ''" />
                </button>

                <!-- Submenu -->
                <div x-show="openMenu" x-collapse x-cloak
                    class="ml-6 mt-1 space-y-0.5 border-l-2 border-neutral-200 dark:border-neutral-700 pl-3">

                    <!-- Company Settings -->
                    @can('company_settings.view')
                        <a href="{{ route('company-settings.show') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
            {{ request()->routeIs('company-settings.*')
                ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-neutral-800 dark:text-white'
                : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                            {{-- <x-lucide-building-2 class="w-5 h-5" /> --}}
                            Company Settings
                        </a>
                    @endcan
                    <!-- Roles & Permissions -->
                    @can('roles.view')
                        <a href="{{ route('roles.manage') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
            {{ request()->routeIs('roles.manage')
                ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-neutral-800 dark:text-white'
                : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                            {{-- <x-lucide-user-round-key class="w-5 h-5" /> --}}
                            Roles & Permissions
                        </a>
                    @endcan

                    <!-- Roles & Permissions -->

                    <a href="{{ route('settings.2mfa') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
            {{ request()->routeIs('settings.2mfa')
                ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-neutral-800 dark:text-white'
                : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                        {{-- <x-lucide-shield-check class="w-5 h-5" /> --}}
                        Two-Factor Auth
                    </a>


                </div>
            </div>
        @endcanany


        <!-- Templates -->
        <a href="{{ route('support.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors border-l-2
            {{ request()->routeIs('support.*')
                ? 'bg-primary-50 text-primary-700 font-semibold border-primary-600 dark:bg-neutral-800 dark:text-white dark:border-white'
                : 'text-neutral-600 dark:text-neutral-400 border-transparent hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
            <x-lucide-headset class="w-5 h-5" />
            Supports
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

                        {{-- <x-lucide-sun class="w-4 h-4" /> --}}
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

                        {{-- <x-lucide-moon class="w-4 h-4" /> --}}
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

                        {{-- <x-lucide-monitor class="w-4 h-4" /> --}}
                        System
                    </button>

                </div>
            </div>


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

            @if (auth()->user()->avatar)
                <img class="w-9 h-9 rounded-full border border-neutral-200 dark:border-neutral-700 shrink-0 object-cover"
                    src="{{ Storage::disk('s3')->url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
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

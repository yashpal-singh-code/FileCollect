<header x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 10"
    :class="scrolled
        ?
        'shadow-[0_6px_30px_rgba(0,0,0,0.08)] border-slate-300' :
        'shadow-none border-slate-200'"
    class="bg-white/80 backdrop-blur-md sticky top-0 z-50 transition-all duration-300">


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between h-16">

            {{-- LOGO --}}
            <a href="/#" class="flex items-center">

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

            {{-- DESKTOP NAV --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">

                <a href="/#features" class="hover:text-blue-600 transition">Features</a>
                <a href="/#solutions" class="hover:text-blue-600 transition">Solutions</a>
                <a href="/#pricing" class="hover:text-blue-600 transition">Pricing</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-600 transition">Contact Us</a>

            </nav>

            {{-- DESKTOP CTA --}}
            <div class="hidden md:flex items-center gap-4">

                <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-blue-600 transition">
                    Sign In
                </a>

                <a href="#pricing"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow hover:bg-blue-700 transition">
                    Get Started
                </a>

            </div>

            {{-- MOBILE BUTTON --}}
            <button @click="open = !open"
                class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 text-slate-600">

                <x-lucide-menu class="w-5 h-5" x-show="!open" />
                <x-lucide-x class="w-5 h-5" x-show="open" />

            </button>

        </div>

    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4" @click.outside="open = false"
        class="md:hidden border-t border-slate-200 bg-white/95 backdrop-blur-lg shadow-lg">

        <div class="px-5 py-5 space-y-5 text-sm">

            {{-- MENU LINKS --}}
            <a href="/#features" @click="open = false"
                class="block font-medium text-slate-700 hover:text-blue-600 transition">
                Features
            </a>

            <a href="/#solutions" @click="open = false"
                class="block font-medium text-slate-700 hover:text-blue-600 transition">
                Solutions
            </a>

            <a href="/#pricing" @click="open = false"
                class="block font-medium text-slate-700 hover:text-blue-600 transition">
                Pricing
            </a>

            <a href="{{ route('contact') }}" @click="open = false"
                class="block font-medium text-slate-700 hover:text-blue-600 transition">
                Contact Us
            </a>

            {{-- DIVIDER --}}
            <div class="pt-4 border-t border-slate-200 space-y-3">

                <a href="{{ route('login') }}" @click="open = false"
                    class="block text-slate-600 hover:text-blue-600 transition">
                    Sign In
                </a>

                <a href="#pricing" @click="open = false"
                    class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold transition shadow-sm">
                    Get Started
                </a>

            </div>

        </div>

    </div>

</header>

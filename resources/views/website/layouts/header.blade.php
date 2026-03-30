<header x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between h-16">

            {{-- LOGO --}}
            <a href="/#" class="flex items-center gap-2">

                <div class="flex items-center justify-center w-10 h-10">
                    <svg viewBox="0 0 24 24" class="h-9 w-9 text-blue-600" fill="none">
                        <path d="M6 5h8M6 5v14M6 11h6" stroke="currentColor" stroke-width="2.5" />
                        <path d="M18 7a5 5 0 100 10" stroke="currentColor" stroke-width="2.5" />
                    </svg>
                </div>

                <div class="hidden sm:block">
                    <span class="block text-lg font-semibold text-blue-600 leading-tight">
                        FileCollect
                    </span>
                    <span class="block text-[10px] text-blue-500 leading-tight">
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
    <div x-show="open" x-transition class="md:hidden border-t border-slate-200 bg-white/95 backdrop-blur-lg">

        <div class="px-4 py-4 space-y-4 text-sm">

            <a href="/#features" class="block hover:text-blue-600">Features</a>
            <a href="/#solutions" class="block hover:text-blue-600">Solutions</a>
            <a href="/#pricing" class="block hover:text-blue-600">Pricing</a>
            <a href="{{ route('contact') }}" class="block hover:text-blue-600">Contact Us</a>

            <div class="pt-4 border-t space-y-3">

                <a href="{{ route('login') }}" class="block text-slate-600 hover:text-blue-600">
                    Sign In
                </a>

                <a href="#pricing" class="block text-center bg-blue-600 text-white py-2.5 rounded-xl font-semibold">
                    Get Started
                </a>

            </div>

        </div>

    </div>

</header>

<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
</head>

<body class="min-h-screen bg-gray-100">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- LEFT LOGIN (Mobile First) --}}
        <div class="flex-1 flex items-center justify-center bg-white px-4 sm:px-6 py-10">

            <div class="w-full">

                <!-- Brand -->
                <div class="flex justify-center mb-6">

                    <a href="#" class="flex items-center">

                        <!-- Icon -->
                        <div class="flex items-center justify-center w-11 h-11">
                            <svg viewBox="0 0 24 24" class="h-10 w-10 text-blue-600" fill="none">
                                <path d="M6 5h8M6 5v14M6 11h6" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />

                                <path d="M18 7a5 5 0 100 10" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
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

                @yield('guest')

            </div>

        </div>


        {{-- RIGHT IMAGE --}}
        <div class="hidden lg:flex flex-1 relative overflow-hidden rounded-l-3xl">

            <!-- Background Image -->
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1600"
                class="absolute inset-0 w-full h-full object-cover scale-105">

            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-linear-to-br from-blue-700/80 via-blue-600/70 to-indigo-700/80">
            </div>

            <!-- Soft Glow -->
            <div class="absolute inset-0 bg-black/20 backdrop-blur-[2px]"></div>

            <!-- Content -->
            <div class="relative z-10 flex items-center justify-center w-full px-10 text-white">

                <div class="max-w-lg text-center space-y-5">

                    <!-- Badge -->
                    <span
                        class="inline-flex items-center gap-2 text-xs font-medium
                               bg-white/10 border border-white/20
                               px-3 py-1 rounded-full backdrop-blur">
                        🔐 Trusted by modern teams
                    </span>

                    <!-- Heading -->
                    <h2 class="text-3xl xl:text-4xl font-bold leading-tight">
                        Secure Client Document Collection
                    </h2>

                    <!-- Description -->
                    <p class="text-sm text-white/90 leading-relaxed">
                        FileCollect helps businesses securely collect, organize, and manage
                        client documents with automated workflows, smart reminders, and
                        enterprise-grade security.
                    </p>

                    <!-- CTA -->
                    <div class="pt-2">
                        <a href="#features"
                            class="inline-flex items-center gap-2 text-sm font-semibold
                                   text-white hover:opacity-90 transition">

                            Explore Features

                            <i data-lucide="arrow-right"
                                class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Global Toast (Top Center) -->
    @include('layouts.toaster')

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
</head>


<body class="bg-slate-100 text-slate-800 antialiased pt-16 pb-16">

    <div class="min-h-screen flex flex-col">

        <!-- ================= HEADER ================= -->
        <header class="fixed top-0 left-0 right-0 bg-white border-b border-slate-200 shadow-sm z-50">

            <div class="max-w-7xl mx-auto h-16 flex items-center justify-between px-6">

                <!-- Brand -->
                <a href="#" class="flex items-center">

                    <!-- Icon -->
                    <div class="flex items-center justify-center w-11 h-11">
                        <svg viewBox="0 0 24 24" class="h-10 w-10 text-blue-600" fill="none">
                            <path d="M6 5h8M6 5v14M6 11h6" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round" />

                            <path d="M18 7a5 5 0 100 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>

                    <!-- Brand -->
                    <div class="flex flex-col">
                        <span class="block text-xl font-semibold text-blue-600 leading-tight">
                            FileCollect
                        </span>

                        <span class="block text-xs text-blue-500 leading-tight">
                            Secure • Organize
                        </span>
                    </div>

                </a>

                <!-- Right Side -->
                <div class="flex items-center gap-5">
                    <!-- Divider -->
                    <span class="h-4 w-px bg-slate-200"></span>

                    <!-- SaaS CTA Button -->
                    <a href="{{ route('pricing') }}" target="_blank"
                        class="text-xs sm:text-sm font-medium bg-blue-600 text-white px-3 py-2 sm:px-4 hover:bg-blue-700 transition shadow-sm whitespace-nowrap">
                        Explore
                    </a>

                </div>

            </div>

        </header>


        <!-- ================= MAIN ================= -->
        <main class="flex-1 py-10">

            <div class="max-w-7xl mx-auto px-6">

                @yield('portal')

            </div>

        </main>

    </div>


    <!-- ================= FOOTER ================= -->
    <footer class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200">

        <div class="max-w-7xl mx-auto px-6 h-12 flex items-center justify-between text-xs text-slate-500">

            <!-- Left -->
            <span>
                © {{ date('Y') }} FileCollect
            </span>

            <!-- Center -->
            <span class="text-slate-400 hidden md:block">
                Secure Document Upload Portal
            </span>

            <!-- Right -->
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-blue-600">Privacy</a>
                <a href="#" class="hover:text-blue-600">Terms</a>
            </div>

        </div>

    </footer>

    <!-- Global Toast (Top Center) -->
    @include('layouts.toaster')

</body>

</html>

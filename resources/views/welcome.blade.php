@extends('website.layouts.app')

@section('website')
    {{-- HERO --}}
    <section
        class="relative isolate overflow-hidden text-white min-h-screen flex items-center lg:items-start pt-12 lg:pt-10">

        {{-- 🌈 Background --}}
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-linear-to-br from-blue-600 via-indigo-600 to-purple-700"></div>
            <div
                class="absolute inset-0 opacity-30 blur-3xl bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.3),transparent)]">
            </div>
        </div>

        {{-- CONTAINER --}}
        <div class="max-w-7xl mx-auto w-full px-5 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LEFT CONTENT --}}
            <div class="space-y-6 max-w-2xl mx-auto lg:mx-0 text-center lg:text-left">

                {{-- BADGE --}}
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 text-[10px] sm:text-xs font-semibold uppercase tracking-wider bg-white/10 border border-white/20 rounded-full backdrop-blur-xl animate-[fade-in_0.8s_ease_forwards]">
                    <x-lucide-rocket class="w-3.5 h-3.5 sm:w-4 h-4" />
                    Trusted by modern teams
                </div>

                {{-- HEADING --}}
                <h1
                    class="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight animate-[fade-in-up_0.8s_ease_forwards]">
                    Securely Collect <br class="hidden sm:block"> Client Documents
                    <span class="block text-blue-200 mt-1">
                        Without Email Chaos
                    </span>
                </h1>

                {{-- TEXT --}}
                <p
                    class="text-base sm:text-lg lg:text-xl text-blue-100 max-w-lg mx-auto lg:mx-0 animate-[fade-in-up_0.8s_ease_forwards_0.1s]">
                    FileCollect helps professionals request, track, and manage client documents in one secure portal —
                    without messy email threads.
                </p>

                {{-- CTA --}}
                <div
                    class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 animate-[fade-in-up_0.8s_ease_forwards_0.2s]">
                    <a href="#pricing"
                        class="group bg-white text-blue-700 px-8 py-4 rounded-xl font-bold shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-blue-300/40 flex items-center justify-center gap-2">
                        Get Started Free
                        <x-lucide-arrow-right class="w-5 h-5 group-hover:translate-x-1 transition" />
                    </a>
                </div>

                {{-- TRUST --}}
                <p class="text-xs sm:text-sm text-blue-200 italic animate-[fade-in-up_0.8s_ease_forwards_0.3s]">
                    Free plan • No credit card • Setup in 30 seconds
                </p>
            </div>

            {{-- RIGHT (PREMIUM SHOWCASE) --}}
            <div
                class="relative flex justify-center lg:justify-end items-center animate-[fade-in_1s_ease_forwards] pb-12 lg:pb-0">

                {{-- 🔥 BIG GLOW BACKGROUND --}}
                <div class="absolute -inset-4 sm:-inset-10 bg-blue-500/20 blur-[80px] sm:blur-[120px] rounded-full"></div>

                {{-- 🔥 FLOATING BACK CARD (DEPTH) - Hidden on Mobile for focus --}}
                <div
                    class="hidden sm:block absolute right-6 top-10 w-full max-w-lg h-full bg-white/10 backdrop-blur-xl rounded-2xl border border-white/10 shadow-xl rotate-2">
                </div>

                {{-- 🔥 MAIN CARD --}}
                <div
                    class="relative w-full max-w-[340px] sm:max-w-xl bg-white/95 backdrop-blur-2xl border border-white/30 rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.25)] sm:shadow-[0_30px_100px_rgba(0,0,0,0.35)] p-4 sm:p-6 space-y-4 sm:space-y-5">

                    {{-- HEADER --}}
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-500">
                            Client Documents
                        </h3>
                        <span
                            class="text-[10px] sm:text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full font-medium">
                            Live Update
                        </span>
                    </div>

                    {{-- FILE RECEIVED --}}
                    <div
                        class="flex justify-between items-center bg-blue-50 p-3 rounded-xl border border-blue-100 hover:scale-[1.02] transition cursor-default">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 text-white flex items-center justify-center rounded-lg text-[10px] sm:text-xs font-bold shadow">
                                PDF
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm font-semibold text-slate-700">Tax_Return_2026.pdf</p>
                                <p class="text-[10px] sm:text-xs text-slate-400">Uploaded just now</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded">
                            Received
                        </span>
                    </div>

                    {{-- FILE PENDING --}}
                    <div
                        class="flex justify-between items-center bg-white p-3 rounded-xl border border-slate-200 hover:scale-[1.02] transition cursor-default">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-slate-200 text-slate-500 flex items-center justify-center rounded-lg text-[10px] sm:text-xs font-bold">
                                IMG
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm font-semibold text-slate-700">Driver_License.jpg</p>
                                <p class="text-[10px] sm:text-xs text-slate-400">Waiting for upload</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded">
                            Pending
                        </span>
                    </div>

                    {{-- PROGRESS --}}
                    <div class="p-3 sm:p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex justify-between text-[10px] sm:text-xs text-slate-500 mb-2">
                            <span>Client Uploading...</span>
                            <span class="font-bold">75%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 sm:h-2 overflow-hidden">
                            <div
                                class="bg-linear-to-r from-blue-500 to-indigo-600 h-full rounded-full animate-pulse w-[75%]">
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER ACTION --}}
                    <div class="flex justify-between items-center pt-1 sm:pt-2">
                        <span class="text-[10px] text-slate-400">
                            Last updated 2 min ago
                        </span>
                        <button class="text-[10px] sm:text-xs font-bold text-blue-600 hover:underline">
                            View Workspace →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="features" class="py-14 sm:py-16 lg:py-20 bg-white dark:bg-[#020617]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    Why Professionals Choose FileCollect
                </h2>

                <p class="mt-4 text-sm sm:text-base text-slate-600 dark:text-slate-400">
                    Stop chasing emails, attachments, and missing documents — switch to a smarter, secure workflow.
                </p>

            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- FEATURE 1 (HIGHLIGHTED) --}}
                <div
                    class="relative group text-center p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-red-50 to-white dark:from-white/10 dark:to-white/5 border border-red-200 dark:border-white/10 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    {{-- GLOW --}}
                    <div
                        class="absolute -inset-1 bg-red-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">

                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <x-lucide-alert-circle class="w-6 h-6 sm:w-7 sm:h-7" />
                        </div>

                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Stop Sending Sensitive Files Over Email
                        </h3>

                        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                            Keep IDs, tax docs, and financial files secure with bank-grade encrypted uploads.
                        </p>

                        {{-- TRUST --}}
                        <span class="inline-block mt-3 text-xs font-semibold text-red-600 bg-red-100 px-2 py-1 rounded">
                            Bank-grade security
                        </span>

                    </div>

                </div>

                {{-- FEATURE 2 --}}
                <div
                    class="group text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-clock class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Save 10+ Hours Every Week
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Automated reminders follow up with clients — so you don’t waste time chasing documents.
                    </p>

                </div>

                {{-- FEATURE 3 --}}
                <div
                    class="group text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-check-circle class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Zero Client Friction
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Clients don’t need accounts — just click a secure link and upload instantly.
                    </p>

                </div>

                {{-- FEATURE 4 (UI PREVIEW) --}}
                <div
                    class="group p-6 sm:p-8 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div class="space-y-4">

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Real-Time Upload Tracking
                        </h3>

                        {{-- MINI UI --}}
                        <div class="p-4 bg-gray-50 dark:bg-white/10 rounded-xl border border-gray-200 dark:border-white/10">

                            <div class="flex justify-between text-xs text-gray-500 mb-2">
                                <span>Client Uploading...</span>
                                <span class="font-semibold">75%</span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full w-[75%]"></div>
                            </div>

                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Track uploads live — know exactly what’s completed and what’s pending.
                        </p>

                    </div>

                </div>

                {{-- FEATURE 5 --}}
                <div
                    class="group text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-folder class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Organized Client Workflows
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Automatically organize files by client, request, and category — no chaos.
                    </p>

                </div>

                {{-- FEATURE 6 --}}
                <div
                    class="group text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-bell class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">
                        Smart Notifications
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Get instant alerts when files are uploaded or missing — stay in control.
                    </p>

                </div>

            </div>

            {{-- CTA --}}
            <div class="text-center mt-12">
                <a href="#pricing" class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:underline">
                    Start collecting documents in minutes
                    <x-lucide-arrow-right class="w-4 h-4" />
                </a>
            </div>

        </div>

    </section>


    <section id="solutions" class="py-14 sm:py-16 lg:py-20 bg-slate-900 text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4">
                    Built for Professionals Who Collect Client Documents
                </h2>

                <p class="text-sm sm:text-base lg:text-lg text-slate-400">
                    FileCollect helps professionals securely request, collect,
                    and organize client documents — without email chaos.
                </p>

            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">

                {{-- LEGAL --}}
                <div
                    class="group relative p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -inset-1 bg-blue-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                            <x-lucide-scale class="w-5 h-5 text-blue-400" />
                        </div>

                        <h4 class="text-base sm:text-lg font-semibold mb-2">
                            Legal Professionals
                        </h4>

                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Securely collect contracts, KYC documents, witness statements, and legal evidence with a full
                            audit trail.
                        </p>
                    </div>

                </div>

                {{-- ACCOUNTING --}}
                <div
                    class="group relative p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -inset-1 bg-blue-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                            <x-lucide-calculator class="w-5 h-5 text-blue-400" />
                        </div>

                        <h4 class="text-base sm:text-lg font-semibold mb-2">
                            Accounting & Tax Firms
                        </h4>

                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Automate the collection of receipts, bank statements, payroll documents, and tax files from
                            clients.
                        </p>
                    </div>

                </div>

                {{-- MORTGAGE --}}
                <div
                    class="group relative p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -inset-1 bg-blue-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                            <x-lucide-home class="w-5 h-5 text-blue-400" />
                        </div>

                        <h4 class="text-base sm:text-lg font-semibold mb-2">
                            Mortgage Brokers
                        </h4>

                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Collect payslips, bank statements, and property documents to accelerate loan approvals.
                        </p>
                    </div>

                </div>

                {{-- FINANCIAL --}}
                <div
                    class="group relative p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -inset-1 bg-blue-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                            <x-lucide-trending-up class="w-5 h-5 text-blue-400" />
                        </div>

                        <h4 class="text-base sm:text-lg font-semibold mb-2">
                            Financial Advisors
                        </h4>

                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Request financial statements, onboarding forms, and compliance documents securely.
                        </p>
                    </div>

                </div>

                {{-- HR --}}
                <div
                    class="group relative p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -inset-1 bg-blue-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                            <x-lucide-users class="w-5 h-5 text-blue-400" />
                        </div>

                        <h4 class="text-base sm:text-lg font-semibold mb-2">
                            HR & Recruitment
                        </h4>

                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Collect employee onboarding documents, ID verification files, and contracts.
                        </p>
                    </div>

                </div>

                {{-- BUSINESS --}}
                <div
                    class="group relative p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -inset-1 bg-blue-500/10 blur-xl rounded-2xl opacity-0 group-hover:opacity-100 transition">
                    </div>

                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                            <x-lucide-briefcase class="w-5 h-5 text-blue-400" />
                        </div>

                        <h4 class="text-base sm:text-lg font-semibold mb-2">
                            Businesses & Client Services
                        </h4>

                        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                            Collect contracts, onboarding documents, verification files, and project materials from clients
                            in one secure portal.
                        </p>
                    </div>

                </div>

            </div>

            {{-- RESULT --}}
            <div class="mt-16 sm:mt-20 text-center max-w-2xl mx-auto">

                <p class="text-lg sm:text-xl lg:text-2xl italic text-blue-300 mb-6">
                    Collect, organize, and manage client documents in one secure portal — up to 70% faster.
                </p>

                <div class="flex items-center justify-center gap-3">

                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center">
                        <x-lucide-rocket class="w-5 h-5 text-slate-300" />
                    </div>

                    <div class="text-left">
                        <p class="font-semibold text-sm sm:text-base">Trusted by modern teams</p>
                        <p class="text-xs sm:text-sm text-slate-400">
                            Secure document collection without email chaos.
                        </p>
                    </div>

                </div>

            </div>

            {{-- CTA --}}
            <div class="text-center mt-10">
                <a href="#pricing"
                    class="inline-flex items-center gap-2 bg-white text-slate-900 px-6 py-3 rounded-xl font-semibold shadow-lg hover:-translate-y-1 transition">
                    Start Collecting Documents
                    <x-lucide-arrow-right class="w-4 h-4" />
                </a>
            </div>

        </div>

    </section>


    <section id="how-it-works" class="py-14 sm:py-16 lg:py-20 bg-white dark:bg-[#020617]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    How FileCollect Works
                </h2>

                <p class="mt-4 text-sm sm:text-base text-slate-600 dark:text-slate-400">
                    Request documents from clients using a secure magic link.
                    No accounts, no passwords — just simple and secure uploads.
                </p>

            </div>

            {{-- STEPS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 relative">

                {{-- STEP 1 --}}
                <div
                    class="group relative text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    {{-- STEP NUMBER --}}
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center shadow">
                        1
                    </div>

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-send class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Send a Magic Link
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Create a request and send your client a secure upload link via email or URL.
                    </p>

                </div>

                {{-- STEP 2 --}}
                <div
                    class="group relative text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shadow">
                        2
                    </div>

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-link class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Client Opens the Link
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Clients open the link and access a secure upload page — no login required.
                    </p>

                </div>

                {{-- STEP 3 --}}
                <div
                    class="group relative text-center p-6 sm:p-8 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">

                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center shadow">
                        3
                    </div>

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <x-lucide-upload-cloud class="w-6 h-6 sm:w-7 sm:h-7" />
                    </div>

                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Upload Documents Instantly
                    </h3>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        Clients drag & drop files — you receive organized documents instantly.
                    </p>

                </div>

            </div>

            {{-- CTA --}}
            <div class="text-center mt-12 sm:mt-16">
                <a href="#pricing"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold shadow-lg hover:-translate-y-1 hover:bg-blue-700 transition-all duration-300">

                    Start Collecting Documents
                    <x-lucide-arrow-right class="w-4 h-4" />
                </a>
            </div>

        </div>

    </section>


    <section id="pricing" class="py-14 sm:py-16 lg:py-20 bg-slate-50 dark:bg-[#020617]" x-data="{ billing: 'monthly', loading: false }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                    Simple, Scalable Pricing
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400">
                    Start free. Upgrade as your business grows.
                </p>
            </div>

            {{-- BILLING TOGGLE --}}
            <div class="flex justify-center mb-10 sm:mb-14">
                <div
                    class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-full p-1 flex shadow-sm">

                    <button @click="billing = 'monthly'"
                        :class="billing === 'monthly' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 dark:text-gray-300'"
                        class="px-5 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-medium transition">
                        Monthly
                    </button>

                    <button @click="billing = 'yearly'"
                        :class="billing === 'yearly' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 dark:text-gray-300'"
                        class="px-5 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-medium flex items-center gap-2 transition">

                        Yearly
                        <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full font-semibold">
                            Save 30%
                        </span>

                    </button>

                </div>
            </div>

            {{-- CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                @foreach ($plans as $plan)
                    <div
                        class="relative flex flex-col rounded-2xl border p-6 sm:p-8 bg-white dark:bg-white/5 backdrop-blur
                    transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl

                    {{ $plan->is_popular
                        ? 'border-blue-600 shadow-xl scale-[1.02] lg:scale-105 z-10'
                        : 'border-slate-200 dark:border-white/10' }}">

                        {{-- POPULAR --}}
                        @if ($plan->is_popular)
                            <div
                                class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-4 py-1 rounded-full text-[10px] font-bold tracking-wide shadow">
                                MOST POPULAR
                            </div>
                        @endif

                        {{-- PLAN NAME --}}
                        <h3
                            class="text-lg font-semibold mb-2 {{ $plan->is_popular ? 'text-blue-600' : 'text-gray-900 dark:text-white' }}">
                            {{ $plan->name }}
                        </h3>

                        {{-- PRICE --}}
                        <div class="mb-6">

                            @if ($plan->is_free)
                                <div class="flex items-end gap-1">
                                    <span class="text-3xl sm:text-4xl font-extrabold">₹0</span>
                                    <span class="text-sm text-slate-500">Free</span>
                                </div>
                            @else
                                <div class="flex items-end gap-1">

                                    <span class="text-3xl sm:text-4xl font-extrabold"
                                        x-text="billing === 'monthly'
                                        ? '₹{{ number_format($plan->monthly_price, 0) }}'
                                        : '{{ $plan->yearly_price ? '₹' . number_format($plan->yearly_price, 0) : 'N/A' }}'">
                                    </span>

                                    <span class="text-sm text-slate-500"
                                        x-text="billing === 'monthly' ? '/mo' : '/yr'"></span>

                                </div>

                                <div x-show="billing === 'yearly'" class="text-xs text-green-600 mt-1 font-medium">
                                    Save 30% yearly
                                </div>
                            @endif

                        </div>

                        {{-- FEATURES --}}
                        <ul class="space-y-2 text-sm mb-6">

                            <li class="flex items-center gap-2">
                                <x-lucide-users class="w-4 h-4 text-slate-400" />
                                {{ $plan->company_users ?? 'Unlimited' }} Users
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-user class="w-4 h-4 text-slate-400" />
                                {{ $plan->clients ?? 'Unlimited' }} Clients
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-file-text class="w-4 h-4 text-slate-400" />
                                {{ $plan->document_requests ?? 'Unlimited' }} Requests
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-hard-drive class="w-4 h-4 text-slate-400" />
                                {{ $plan->storage_mb }} MB Storage
                            </li>

                        </ul>

                        {{-- CTA --}}
                        <button
                            @click="
                            if (loading) return;
                            loading = true;
                            window.location.href = '/select-plan?plan={{ $plan->slug }}&billing=' + billing;
                        "
                            :disabled="loading"
                            class="mt-auto w-full py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all

                        {{ $plan->is_popular
                            ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-lg'
                            : 'border border-slate-300 text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10' }}">

                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>

                            <span x-text="loading ? 'Redirecting...' : 'Choose Plan'"></span>

                            <x-lucide-arrow-right class="w-4 h-4" x-show="!loading" />

                        </button>

                    </div>
                @endforeach

            </div>

        </div>

    </section>



    <section class="relative isolate overflow-hidden text-white py-16 sm:py-20 lg:py-24 text-center">

        {{-- 🌈 BACKGROUND --}}
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700"></div>
            <div
                class="absolute inset-0 opacity-30 blur-3xl bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.25),transparent)]">
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADING --}}
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold mb-4 leading-tight">
                Ready to reclaim your time?
            </h2>

            {{-- TEXT --}}
            <p class="text-sm sm:text-base lg:text-lg mb-8 opacity-90">
                Simplify how you collect and manage client documents — securely and effortlessly.
            </p>

            {{-- CTA BUTTON --}}
            <a href="#pricing"
                class="group inline-flex items-center gap-2 bg-white text-blue-700 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold text-sm sm:text-base shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">

                Get Started Free
                <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition" />
            </a>

            {{-- TRUST TEXT --}}
            <p class="mt-6 text-xs sm:text-sm opacity-70 italic">
                No credit card required • Setup in 30 seconds
            </p>

        </div>

    </section>
@endsection

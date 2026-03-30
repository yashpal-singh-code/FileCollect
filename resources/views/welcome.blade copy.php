<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')

    <style>
        .gradient-bg {
            background: linear-gradient(-45deg, #2563eb, #4f46e5, #7c3aed, #0284c7);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <header class="bg-white/80 backdrop-blur-md border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <a href="{{ route('pricing') }}" class="flex items-center">

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

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">

                <a href="/#features" class="hover:text-blue-600 transition">
                    Features
                </a>

                <a href="/#solutions" class="hover:text-blue-600 transition">
                    Solutions
                </a>

                <a href="/#how-it-works" class="hover:text-blue-600 transition">
                    How it works
                </a>

                <a href="/#pricing" class="hover:text-blue-600 transition">
                    Pricing
                </a>

            </nav>


            <!-- Right Side -->
            <div class="flex items-center gap-4">

                @auth

                    <!-- Dashboard Button -->
                    <a href="{{ route('dashboard') }}"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-blue-700 shadow-lg shadow-blue-200 transition flex items-center gap-2">

                        <x-lucide-layout-dashboard class="w-4 h-4" />

                        Dashboard
                    </a>
                @else
                    <!-- Login -->
                    <a href="{{ route('login') }}"
                        class="hidden sm:block text-sm font-medium text-slate-700 hover:text-blue-600 transition">
                        Sign In
                    </a>

                    <!-- Register -->
                    <a href="#pricing"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                        Get Started Free
                    </a>

                @endauth

            </div>

        </div>
    </header>

    <section class="gradient-bg text-white py-24 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT CONTENT --}}
            <div data-aos="fade-right">

                {{-- TRUST BADGE --}}
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 mb-6 text-xs font-bold tracking-wider uppercase bg-white/20 rounded-full backdrop-blur">
                    <x-lucide-rocket class="w-5 h-5" />
                    Trusted by modern teams & professionals
                </div>

                {{-- HEADLINE --}}
                <h1 class="text-5xl lg:text-6xl font-extrabold mb-6 leading-tight tracking-tight">
                    Securely Collect Client Documents
                    <span class="block text-blue-200">Without Email Chaos</span>
                </h1>

                {{-- SUBTEXT --}}
                <p class="text-xl opacity-90 mb-10 leading-relaxed max-w-xl">
                    Request, track, and manage client files in one secure workspace.
                    <span class="font-semibold text-white">No logins. No confusion. Just faster workflows.</span>
                </p>

                {{-- CTA --}}
                <div class="flex flex-col sm:flex-row gap-4">

                    <a href="#pricing"
                        class="bg-white text-blue-700 px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 transition shadow-2xl flex items-center justify-center gap-2 group">
                        Get Started Free
                        <x-lucide-arrow-right class="w-5 h-5 group-hover:translate-x-1 transition" />
                    </a>

                    <a href="#demo"
                        class="px-8 py-4 rounded-xl font-semibold text-lg border border-white/40 hover:bg-white/10 transition flex items-center justify-center gap-2 backdrop-blur">
                        Book Demo
                    </a>

                </div>

                {{-- TRUST LINE --}}
                <p class="mt-6 text-sm text-blue-100 italic">
                    Free plan available • No credit card required • Setup in 30 seconds
                </p>

            </div>

            {{-- RIGHT UI MOCK --}}
            <div data-aos="fade-left" class="relative">

                {{-- GLOW --}}
                <div class="bg-blue-500/20 absolute inset-0 blur-3xl rounded-full"></div>

                {{-- CARD --}}
                <div class="relative glass-card rounded-2xl shadow-2xl p-4 border border-white/40 backdrop-blur-xl">

                    <div class="space-y-4">

                        {{-- FILE RECEIVED --}}
                        <div
                            class="flex justify-between items-center bg-blue-50 p-3 rounded-lg border border-blue-100 hover:scale-[1.02] transition">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white text-xs font-bold">
                                    PDF
                                </div>
                                <span class="text-sm font-semibold text-slate-700">Tax_Return_2026.pdf</span>
                            </div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                Received
                            </span>
                        </div>

                        {{-- FILE PENDING --}}
                        <div
                            class="flex justify-between items-center bg-white p-3 rounded-lg border border-slate-100 hover:scale-[1.02] transition">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-slate-200 rounded flex items-center justify-center text-slate-500 text-xs font-bold">
                                    IMG
                                </div>
                                <span class="text-sm font-semibold text-slate-700">Driver_License.jpg</span>
                            </div>
                            <span class="text-xs font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded">
                                Pending
                            </span>
                        </div>

                        {{-- UPLOAD PROGRESS --}}
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="flex justify-between text-xs text-slate-500 mb-2">
                                <span>Client Uploading...</span>
                                <span>75%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full animate-pulse" style="width: 75%"></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl font-bold mb-6">Why Professionals Choose FileCollect</h2>
                <p class="text-lg text-slate-600">Traditional email is slow, insecure, and disorganized. We've built a
                    better way to handle sensitive client data.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">

                <div class="text-center" data-aos="fade-up">
                    <div
                        class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-lucide-alert-circle class="w-8 h-8" />
                    </div>

                    <h3 class="text-xl font-bold mb-3">Email is a Security Risk</h3>

                    <p class="text-slate-600">
                        Don't risk sensitive IDs or financial statements sitting in unencrypted inboxes.
                        Use our bank-grade encrypted portal.
                    </p>
                </div>


                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-lucide-clock class="w-8 h-8" />
                    </div>

                    <h3 class="text-xl font-bold mb-3">Save 10+ Hours Weekly</h3>

                    <p class="text-slate-600">
                        Automated reminders do the "chasing" for you, so you can focus on
                        billable work instead of checking your inbox.
                    </p>
                </div>


                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-lucide-check-circle class="w-8 h-8" />
                    </div>

                    <h3 class="text-xl font-bold mb-3">Zero Client Friction</h3>

                    <p class="text-slate-600">
                        Clients don't need to register or create passwords.
                        They click a secure link, drag, and drop.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section id="solutions" class="py-24 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-bold mb-6">
                    Built for Professionals Who Collect Client Documents
                </h2>

                <p class="text-slate-400 text-lg">
                    FileCollect helps professionals securely request, collect,
                    and organize client documents in one place — without email chaos.
                </p>
            </div>


            <!-- Industry Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Legal -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <x-lucide-scale class="w-6 h-6 text-blue-400" />
                    </div>

                    <h4 class="text-lg font-bold mb-2">Legal Professionals</h4>

                    <p class="text-slate-400 text-sm">
                        Securely collect contracts, KYC documents, witness
                        statements, and legal evidence with a full audit trail.
                    </p>
                </div>


                <!-- Accounting -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <x-lucide-calculator class="w-6 h-6 text-blue-400" />
                    </div>

                    <h4 class="text-lg font-bold mb-2">Accounting & Tax Firms</h4>

                    <p class="text-slate-400 text-sm">
                        Automate the collection of receipts, bank statements,
                        payroll documents, and tax files from clients.
                    </p>
                </div>


                <!-- Mortgage -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <x-lucide-home class="w-6 h-6 text-blue-400" />
                    </div>

                    <h4 class="text-lg font-bold mb-2">Mortgage Brokers</h4>

                    <p class="text-slate-400 text-sm">
                        Collect payslips, bank statements, and property
                        documents to accelerate loan approvals.
                    </p>
                </div>


                <!-- Financial Advisors -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <x-lucide-trending-up class="w-6 h-6 text-blue-400" />
                    </div>

                    <h4 class="text-lg font-bold mb-2">Financial Advisors</h4>

                    <p class="text-slate-400 text-sm">
                        Request financial statements, onboarding forms,
                        and compliance documents securely.
                    </p>
                </div>


                <!-- HR -->
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <x-lucide-users class="w-6 h-6 text-blue-400" />
                    </div>

                    <h4 class="text-lg font-bold mb-2">HR & Recruitment</h4>

                    <p class="text-slate-400 text-sm">
                        Collect employee onboarding documents, ID
                        verification files, and contracts.
                    </p>
                </div>


                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <x-lucide-briefcase class="w-6 h-6 text-blue-400" />
                    </div>

                    <h4 class="text-lg font-bold mb-2">Businesses & Client Services</h4>

                    <p class="text-slate-400 text-sm">
                        Collect contracts, onboarding documents, verification files,
                        and project materials from clients in one secure portal.
                    </p>
                </div>

            </div>


            <!-- Result / Social Proof -->
            <div class="mt-20 text-center max-w-2xl mx-auto">

                <p class="text-2xl italic text-blue-300 mb-6">
                    Collect, organize, and manage client documents in one secure portal — up to 70% faster with
                    FileCollect.
                </p>

                <div class="flex items-center justify-center gap-3">

                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center">
                        <x-lucide-rocket class="w-5 h-5 text-slate-300" />
                    </div>

                    <div class="text-left">
                        <p class="font-semibold">Trusted by modern teams</p>
                        <p class="text-sm text-slate-400">
                            Securely collect and manage client documents without email chaos.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl font-bold mb-6">
                    How FileCollect Works
                </h2>

                <p class="text-lg text-slate-600">
                    Request documents from clients using a secure magic link.
                    No accounts, no passwords — just simple and secure file uploads.
                </p>
            </div>


            <!-- Steps -->
            <div class="grid md:grid-cols-3 gap-12 text-center">

                <!-- Step 1 -->
                <div>
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-lucide-send class="w-8 h-8" />
                    </div>

                    <h3 class="text-xl font-bold mb-3">
                        Send a Magic Link
                    </h3>

                    <p class="text-slate-600">
                        Create a document request and send your client a secure
                        magic upload link via email or shareable URL.
                    </p>
                </div>


                <!-- Step 2 -->
                <div>
                    <div
                        class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-lucide-link class="w-8 h-8" />
                    </div>

                    <h3 class="text-xl font-bold mb-3">
                        Client Opens the Link
                    </h3>

                    <p class="text-slate-600">
                        Clients click the magic link and instantly access a secure
                        upload page — no account or password required.
                    </p>
                </div>


                <!-- Step 3 -->
                <div>
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-lucide-upload-cloud class="w-8 h-8" />
                    </div>

                    <h3 class="text-xl font-bold mb-3">
                        Upload Documents Instantly
                    </h3>

                    <p class="text-slate-600">
                        Clients drag and drop files, and you instantly receive
                        organized documents in your dashboard.
                    </p>
                </div>

            </div>
        </div>
        <div class="text-center mt-16">
            <a href="#pricing"
                class="bg-blue-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-blue-700 transition shadow-lg">
                Start Collecting Documents
            </a>
        </div>

    </section>

    <section id="pricing" class="py-24 bg-slate-50" x-data="{ billing: 'monthly', loading: false }">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Scalable Plans for Every Team</h2>
                <p class="text-slate-600">Start for free, upgrade as you grow.</p>
            </div>

            <!-- Billing Toggle -->
            <div class="flex justify-center mb-16">
                <div class="bg-white border border-slate-200 rounded-full p-1 flex shadow-sm items-center">

                    <button type="button" @click="billing = 'monthly'"
                        :class="billing === 'monthly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700'"
                        class="px-6 py-2 rounded-full text-sm font-medium transition cursor-pointer">
                        Monthly
                    </button>

                    <button type="button" @click="billing = 'yearly'"
                        :class="billing === 'yearly' ? 'bg-indigo-600 text-white shadow' : 'text-gray-700'"
                        class="px-6 py-2 rounded-full text-sm font-medium transition flex items-center gap-2 cursor-pointer">

                        Yearly
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                            Save 30%
                        </span>

                    </button>

                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

                @foreach ($plans as $plan)
                    <div
                        class="bg-white p-10 rounded-3xl shadow-sm border flex flex-col
                    {{ $plan->is_popular ? 'border-blue-600 shadow-2xl scale-105 relative z-10' : 'border-slate-200' }}">

                        @if ($plan->is_popular)
                            <div
                                class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest">
                                Most Popular
                            </div>
                        @endif

                        <!-- Plan Name -->
                        <h3 class="text-xl font-bold mb-2 {{ $plan->is_popular ? 'text-blue-600' : '' }}">
                            {{ $plan->name }}
                        </h3>

                        <!-- Price -->
                        <div class="mb-8">

                            @if ($plan->is_free)
                                <div class="flex items-baseline">
                                    <span class="text-4xl font-extrabold">₹0</span>
                                    <span class="text-slate-500 ml-2">Free</span>
                                </div>
                            @else
                                <div class="flex items-baseline">

                                    <span class="text-4xl font-extrabold"
                                        x-text="billing === 'monthly'
                                        ? '₹{{ number_format($plan->monthly_price, 0) }}'
                                        : '{{ $plan->yearly_price ? '₹' . number_format($plan->yearly_price, 0) : 'N/A' }}'">
                                    </span>

                                    <span class="text-slate-500 ml-2"
                                        x-text="billing === 'monthly' ? '/month' : '/year'">
                                    </span>

                                </div>

                                <div x-show="billing === 'yearly'" class="text-xs text-green-600 font-medium mt-1">
                                    Save 30% with yearly billing
                                </div>
                            @endif

                        </div>

                        <ul class="space-y-3 text-sm mb-6">

                            {{-- 👥 Core Limits --}}
                            <li class="flex items-center gap-2">
                                <x-lucide-users class="w-4 h-4 text-slate-500" />
                                {{ $plan->company_users ?? 'Unlimited' }} Company Users
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-user class="w-4 h-4 text-slate-500" />
                                {{ $plan->clients ?? 'Unlimited' }} Clients
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-file-text class="w-4 h-4 text-slate-500" />
                                {{ $plan->document_requests ?? 'Unlimited' }} Document Requests
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-layout-template class="w-4 h-4 text-slate-500" />
                                {{ $plan->template_limit ?? 'Unlimited' }} Templates
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-copy class="w-4 h-4 text-slate-500" />
                                {{ $plan->request_templates ?? 'Unlimited' }} Request Templates
                            </li>

                            {{-- 💾 Storage --}}
                            <li class="flex items-center gap-2">
                                <x-lucide-hard-drive class="w-4 h-4 text-slate-500" />
                                {{ $plan->storage_mb }} MB Storage
                            </li>

                            <li class="flex items-center gap-2">
                                <x-lucide-upload class="w-4 h-4 text-slate-500" />
                                {{ $plan->file_size_limit_mb }} MB File Size Limit
                            </li>

                            {{-- 🔁 Reset --}}
                            <li class="flex items-center gap-2">
                                <x-lucide-refresh-cw class="w-4 h-4 text-slate-500" />
                                {{ ucfirst($plan->usage_reset_type) }} Usage Reset
                            </li>

                            {{-- 📦 Upload Features --}}
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <x-lucide-archive class="w-4 h-4 text-slate-500" />
                                    <span>ZIP Upload</span>
                                </div>
                                @if ($plan->allow_zip)
                                    <span class="flex items-center gap-1 text-green-600 text-xs font-medium">
                                        <x-lucide-check class="w-4 h-4" /> Enabled
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-red-500 text-xs font-medium">
                                        <x-lucide-x class="w-4 h-4" /> Disabled
                                    </span>
                                @endif
                            </li>

                            {{-- 🚀 Feature Flags --}}
                            @php
                                $features = [
                                    'Client Portal' => $plan->client_portal,
                                    'MFA Authentication' => $plan->mfa_authentication,
                                    'Download ZIP' => $plan->download_zip,
                                    'Expiry Tracking' => $plan->expiry_tracking,
                                    'Branding' => $plan->branding,
                                    'White Label' => $plan->white_label,
                                    'Priority Support' => $plan->priority_support,
                                ];
                            @endphp

                            @foreach ($features as $label => $enabled)
                                <li class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-check-circle class="w-4 h-4 text-slate-500" />
                                        <span>{{ $label }}</span>
                                    </div>

                                    @if ($enabled)
                                        <span class="flex items-center gap-1 text-green-600 text-xs font-medium">
                                            <x-lucide-check class="w-4 h-4" />
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 text-red-400 text-xs font-medium">
                                            <x-lucide-x class="w-4 h-4" />
                                        </span>
                                    @endif
                                </li>
                            @endforeach

                        </ul>

                        <!-- CTA BUTTON -->
                        <button type="button"
                            @click="
                            if (loading) return;
                            loading = true;
                            window.location.href = '/select-plan?plan={{ $plan->slug }}&billing=' + billing;
                        "
                            :disabled="loading"
                            class="w-full py-4 rounded-xl font-semibold
                        flex items-center justify-center gap-2
                        transition-all duration-200 ease-out
                        focus:outline-none focus:ring-2 focus:ring-blue-500/40
                        group
                        disabled:opacity-50 disabled:cursor-not-allowed

                        {{ $plan->is_popular
                            ? 'bg-blue-600 text-white shadow-lg hover:bg-blue-700 hover:shadow-xl active:scale-[0.98]'
                            : 'border-2 border-slate-200 text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 active:scale-[0.98]' }}">

                            <!-- Loader -->
                            <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>

                            <span x-text="loading ? 'Redirecting...' : 'Get {{ $plan->name }}'"></span>

                            <i data-lucide="arrow-right"
                                class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1"
                                x-show="!loading">
                            </i>

                        </button>

                    </div>
                @endforeach

            </div>

        </div>

    </section>

    {{-- <section id="faq" class="py-24 bg-white" x-data="{ active: null }">
        <div class="max-w-3xl mx-auto px-6">

            <h2 class="text-4xl font-bold mb-12 text-center">
                Frequently Asked Questions
            </h2>

            <div class="space-y-4">

                <!-- FAQ 1 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Is FileCollect secure for sensitive client documents?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 1 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 1" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        Yes. Files are securely stored using AWS cloud infrastructure to keep your documents safe.
                    </div>
                </div>


                <!-- FAQ 2 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 2 ? null : 2)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Do clients need to create an account to upload documents?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 2 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 2" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        No. Clients simply click the secure magic upload link you send them.
                        They can upload files instantly without creating an account or remembering
                        a password.
                    </div>
                </div>


                <!-- FAQ 3 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 3 ? null : 3)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">What types of files can clients upload?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 3 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 3" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        Clients can upload common file types such as PDFs, images, spreadsheets,
                        ZIP files, and Word documents. FileCollect is designed to support
                        tax documents, contracts, onboarding paperwork, financial statements,
                        and identification files.
                    </div>
                </div>


                <!-- FAQ 4 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 4 ? null : 4)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Can I track which documents clients have uploaded?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 4 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 4" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        Yes. FileCollect provides real-time document tracking so you can
                        easily see which files have been received, which are still pending,
                        and which clients need reminders.
                    </div>
                </div>


                <!-- FAQ 5 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 5 ? null : 5)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Who is FileCollect designed for?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 5 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 5" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        FileCollect is designed for professionals who regularly collect
                        documents from clients including accountants, tax professionals,
                        lawyers, mortgage brokers, financial advisors, consultants,
                        agencies, and businesses.
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 7 ? null : 7)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Does FileCollect send automatic reminders?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 7 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 7" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        Yes. FileCollect can send automated reminders to clients who
                        have not uploaded required documents, helping you reduce
                        manual follow-ups.
                    </div>
                </div>


                <!-- FAQ 8 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 8 ? null : 8)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Can clients upload documents from mobile devices?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 8 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 8" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        Yes. The upload portal works on all modern devices including
                        smartphones, tablets, and desktop computers.
                    </div>
                </div>


                <!-- FAQ 9 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 9 ? null : 9)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">Where are uploaded documents stored?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 9 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 9" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        All documents are stored securely on Amazon AWS S3 cloud
                        infrastructure which provides high availability, redundancy,
                        and strong data protection.
                    </div>
                </div>


                <!-- FAQ 10 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button @click="active = (active === 10 ? null : 10)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-slate-50 transition">
                        <span class="font-bold">How does FileCollect help save time?</span>
                        <span class="text-blue-600 font-bold" x-text="active === 10 ? '−' : '+'"></span>
                    </button>

                    <div x-show="active === 10" class="p-6 bg-slate-50 text-slate-600 border-t border-slate-200"
                        x-cloak>
                        By replacing email attachments and scattered file links with
                        a structured document portal, FileCollect helps professionals
                        collect documents faster and reduce manual follow-ups.
                    </div>
                </div>

            </div>
        </div>
    </section> --}}

    <section class="gradient-bg text-white py-20 text-center">
        <h2 class="text-4xl font-extrabold mb-8">Ready to reclaim your time?</h2>

        <p class="text-xl mb-10 opacity-90 max-w-2xl mx-auto">
            Simplify the way you collect and manage client documents with FileCollect.
        </p>

        <a href="#pricing"
            class="bg-white text-blue-700 px-10 py-5 rounded-full font-bold text-xl hover:shadow-2xl transition inline-block">
            Get Started Free
        </a>

        <p class="mt-8 text-sm opacity-70 italic">
            No credit card required • Instant setup
        </p>
    </section>

    <footer class="bg-slate-950 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <!-- Brand -->
            <div class="flex justify-center mb-6">

                <a href="#" class="flex items-center ">

                    <!-- Icon -->
                    <div class="flex items-center justify-center w-11 h-11">
                        {{-- <img src="{{ asset('img/logo.svg') }}"> --}}
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

            <!-- Description -->
            <p class="max-w-xl mx-auto mb-8">
                A simple and secure way to request, collect, and manage client documents.
            </p>

            <!-- Links -->
            <div class="flex flex-wrap justify-center gap-6 text-sm mb-8">
                <a href="#features" class="hover:text-white transition">Features</a>
                <a href="#pricing" class="hover:text-white transition">Pricing</a>
                <a href="#solutions" class="hover:text-white transition">Solutions</a>
                <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer"
                    class="hover:text-white transition">
                    Privacy Policy
                </a>

                <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer"
                    class="hover:text-white transition">
                    Terms & Conditions
                </a>
            </div>

            <!-- Copyright -->
            <p class="text-xs border-t border-slate-800 pt-6">
                © {{ date('Y') }} FileCollect. All rights reserved.
            </p>

        </div>
    </footer>

</body>

</html>

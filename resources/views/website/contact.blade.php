@extends('website.layouts.app')

@section('website')
    <section id="contact" class="scroll-mt-24 relative bg-white dark:bg-[#020617] py-20 sm:py-24 lg:py-28 overflow-hidden">

        {{-- BACKGROUND GLOW --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/10 via-transparent to-purple-500/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="text-center max-w-3xl mx-auto mb-16">

                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">
                    <x-lucide-life-buoy class="w-4 h-4" />
                    24/7 Support
                </span>

                <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                    We’re here to simplify your
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                        document collection
                    </span>
                </h1>

                <p class="mt-5 text-base sm:text-lg text-slate-600 dark:text-slate-400">
                    Questions, issues, or custom needs — our team is ready to help you scale with FileCollect.
                </p>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

                {{-- LEFT SIDE --}}
                <div class="space-y-8">

                    {{-- CONTACT INFO --}}
                    <div class="space-y-6">

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-600 rounded-xl shadow-sm">
                                <x-lucide-mail class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Email Support</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">support@filecollect.in</p>
                                <p class="text-xs text-slate-500 mt-1">Avg response: under 12 hours</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-green-100 text-green-600 rounded-xl shadow-sm">
                                <x-lucide-phone class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Phone</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">+91 XXXXX XXXXX</p>
                                <p class="text-xs text-slate-500 mt-1">Mon–Fri, 9AM–6PM IST</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-purple-100 text-purple-600 rounded-xl shadow-sm">
                                <x-lucide-map-pin class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Location</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">India</p>
                            </div>
                        </div>

                    </div>

                    {{-- TRUST BOX --}}
                    <div
                        class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-sm">
                        <p class="font-semibold text-gray-900 dark:text-white mb-2">
                            🚀 Fast & Reliable Support
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            We usually respond within hours. For urgent issues, reach out via phone or priority email.
                        </p>
                    </div>

                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('contact.submit') }}"
                    class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-6 sm:p-8 space-y-5 shadow-xl backdrop-blur">

                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium">Name</label>
                            <input type="text" name="name" placeholder="Enter your full name"
                                class="w-full mt-1 px-4 py-2.5 rounded-xl border border-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Email</label>
                            <input type="email" name="email" placeholder="you@company.com"
                                class="w-full mt-1 px-4 py-2.5 rounded-xl border border-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                        </div>

                    </div>

                    <div>
                        <label class="text-sm font-medium">Subject</label>
                        <input type="text" name="subject" placeholder="What is this about?"
                            class="w-full mt-1 px-4 py-2.5 rounded-xl border border-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Message</label>
                        <textarea name="message" rows="4" placeholder="Tell us how we can help you..."
                            class="w-full mt-1 px-4 py-2.5 rounded-xl border border-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm"></textarea>
                    </div>

                    <button
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-lg">
                        Send Message
                    </button>

                    <p class="text-xs text-center text-slate-500">
                        🔒 Your data is secure. We never share your information.
                    </p>

                </form>

            </div>

        </div>

    </section>
@endsection

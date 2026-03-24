@extends('layouts.app')

@section('title', 'My Profile')
@section('description', 'Manage your account settings.')

@section('content')
    <div class="max-w-4xl mx-auto">

        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <x-lucide-user class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            My Profile
                        </h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                            Update your personal information and security settings
                        </p>
                    </div>
                </div>
            </div>

            <div x-data="{ tab: 'profile' }">

                {{-- TABS --}}
                <div class="border-b border-neutral-200 dark:border-neutral-800 px-6">
                    <div class="flex gap-6 text-sm font-medium">

                        <button type="button" @click="tab = 'profile'"
                            :class="tab === 'profile'
                                ?
                                'text-primary-600 border-b-2 border-primary-600' :
                                'text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'"
                            class="py-4 transition cursor-pointer">
                            Profile Information
                        </button>

                        <button type="button" @click="tab = 'security'"
                            :class="tab === 'security'
                                ?
                                'text-primary-600 border-b-2 border-primary-600' :
                                'text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'"
                            class="py-4 transition cursor-pointer">
                            Change Password
                        </button>

                    </div>
                </div>

                <div class="p-6 space-y-8">
                    {{-- ================= PROFILE TAB ================= --}}
                    <div x-show="tab === 'profile'" x-cloak>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- FIRST NAME --}}
                                <div>
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        First Name
                                    </label>
                                    <div class="relative">
                                        <x-lucide-user
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="text" name="first_name" placeholder="Enter first name"
                                            value="{{ old('first_name', $user->first_name) }}" required
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                {{-- LAST NAME --}}
                                <div>
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Last Name
                                    </label>
                                    <div class="relative">
                                        <x-lucide-user
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="text" name="last_name" placeholder="Enter last name"
                                            value="{{ old('last_name', $user->last_name) }}" required
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                {{-- EMAIL --}}
                                <div>
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Email Address
                                    </label>
                                    <div class="relative">
                                        <x-lucide-mail
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="email" name="email" placeholder="example@company.com"
                                            value="{{ old('email', $user->email) }}" required
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                {{-- PHONE --}}
                                <div>
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Phone Number
                                    </label>
                                    <div class="relative">
                                        <x-lucide-phone
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="text" name="phone" placeholder="+91 98765 43210"
                                            value="{{ old('phone', $user->phone) }}"
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                {{-- JOB TITLE --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Job Title
                                    </label>
                                    <div class="relative">
                                        <x-lucide-briefcase
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="text" name="job_title" placeholder="Enter job title"
                                            value="{{ old('job_title', $user->job_title) }}"
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                {{-- AVATAR --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Profile Picture
                                    </label>

                                    @if ($user->avatar)
                                        <div class="mb-3">
                                            <img src="{{ Storage::disk('s3')->url($user->avatar) }}"
                                                class="h-16 w-16 rounded-full object-cover border border-neutral-300 dark:border-neutral-700">
                                        </div>
                                    @endif

                                    <input type="file" name="avatar" class="block w-full text-sm ">
                                </div>

                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="h-9 px-5 inline-flex items-center justify-center gap-2
               text-sm font-semibold border
               text-primary-600 border-primary-600/30 bg-primary-600/10
               hover:bg-primary-600 hover:text-white
               transition cursor-pointer">
                                    <x-lucide-save class="w-4 h-4" />
                                    Save Changes
                                </button>
                            </div>

                        </form>
                    </div>

                    {{-- ================= SECURITY TAB ================= --}}
                    <div x-show="tab === 'security'" x-cloak>

                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="md:col-span-2">
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Current Password
                                    </label>
                                    <div class="relative">
                                        <x-lucide-lock
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="password" name="current_password"
                                            placeholder="Enter your current password" required
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        New Password
                                    </label>
                                    <div class="relative">
                                        <x-lucide-lock
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="password" name="password" placeholder="Minimum 8 characters" required
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                        Confirm New Password
                                    </label>
                                    <div class="relative">
                                        <x-lucide-lock
                                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                        <input type="password" name="password_confirmation"
                                            placeholder="Re-enter new password" required
                                            class="w-full h-9 pl-10 pr-3 text-sm
                   bg-white dark:bg-neutral-900
                   text-neutral-900 dark:text-neutral-100
                   border border-neutral-300 dark:border-neutral-700
                   focus:outline-none focus:border-primary-500
                   transition ">
                                    </div>
                                </div>

                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="h-9 px-5 inline-flex items-center justify-center gap-2
               text-sm font-semibold border
               text-primary-600 border-primary-600/30 bg-primary-600/10
               hover:bg-primary-600 hover:text-white
               transition cursor-pointer">
                                    <x-lucide-shield-check class="w-4 h-4" />
                                    Update Password
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

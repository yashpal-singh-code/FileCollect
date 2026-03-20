@extends('layouts.app')

@section('title', 'Create User')
@section('description', 'Add a new team member.')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-8 py-6">

        <div class="max-w-4xl mx-auto">

            <div class="bg-white dark:bg-neutral-900
                    border border-neutral-200 dark:border-neutral-800">

                {{-- HEADER --}}
                <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">

                    <div class="flex items-start justify-between gap-4">

                        {{-- LEFT SIDE --}}
                        <div class="flex items-center gap-3">
                            <x-lucide-user-plus class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                            <div>
                                <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                                    Create User
                                </h1>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                    Add a new team member to your company
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="p-6">

                    @if ($errors->any())
                        <div
                            class="mb-5 p-4 border
                                border-red-300 bg-red-50
                                dark:bg-red-900/20 dark:border-red-800">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- BASIC INFO --}}
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
                                        value="{{ old('first_name') }}" required
                                        class="w-full h-9 pl-10 pr-3 text-sm
                              bg-white dark:bg-neutral-900
                              text-neutral-900 dark:text-neutral-100
                              border border-neutral-300 dark:border-neutral-700
                              focus:outline-none focus:border-primary-500
                              transition">
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
                                        value="{{ old('last_name') }}" required
                                        class="w-full h-9 pl-10 pr-3 text-sm
                              bg-white dark:bg-neutral-900
                              text-neutral-900 dark:text-neutral-100
                              border border-neutral-300 dark:border-neutral-700
                              focus:outline-none focus:border-primary-500
                              transition">
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
                                        value="{{ old('email') }}" required
                                        class="w-full h-9 pl-10 pr-3 text-sm
                              bg-white dark:bg-neutral-900
                              text-neutral-900 dark:text-neutral-100
                              border border-neutral-300 dark:border-neutral-700
                              focus:outline-none focus:border-primary-500
                              transition">
                                </div>
                            </div>

                            {{-- ROLE --}}
                            <div>
                                <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                    Role
                                </label>
                                <div class="relative">
                                    <x-lucide-shield
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />

                                    <select name="role" required
                                        class="w-full h-9 pl-10 pr-3 text-sm
                               bg-white dark:bg-neutral-900
                               text-neutral-900 dark:text-neutral-100
                               border border-neutral-300 dark:border-neutral-700
                               focus:outline-none focus:border-primary-500
                               transition">

                                        <option value="">Select role</option>

                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- PHONE NUMBER --}}
                            <div>
                                <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                    Phone Number
                                </label>
                                <div class="relative">
                                    <x-lucide-phone
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                    <input type="text" name="phone" placeholder="Enter phone number"
                                        value="{{ old('phone') }}"
                                        class="w-full h-9 pl-10 pr-3 text-sm
                      bg-white dark:bg-neutral-900
                      text-neutral-900 dark:text-neutral-100
                      border border-neutral-300 dark:border-neutral-700
                      focus:outline-none focus:border-primary-500
                      transition">
                                </div>
                            </div>

                            {{-- JOB TITLE --}}
                            <div>
                                <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                    Job Title
                                </label>
                                <div class="relative">
                                    <x-lucide-briefcase
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                    <input type="text" name="job_title" placeholder="Enter job title"
                                        value="{{ old('job_title') }}"
                                        class="w-full h-9 pl-10 pr-3 text-sm
                      bg-white dark:bg-neutral-900
                      text-neutral-900 dark:text-neutral-100
                      border border-neutral-300 dark:border-neutral-700
                      focus:outline-none focus:border-primary-500
                      transition">
                                </div>
                            </div>

                        </div>


                        {{-- PASSWORD --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                    Password
                                </label>
                                <div class="relative">
                                    <x-lucide-lock
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                    <input type="password" name="password" placeholder="Enter password" required
                                        class="w-full h-9 pl-10 pr-3 text-sm
                              bg-white dark:bg-neutral-900
                              text-neutral-900 dark:text-neutral-100
                              border border-neutral-300 dark:border-neutral-700
                              focus:outline-none focus:border-primary-500
                              transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <x-lucide-lock
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />
                                    <input type="password" name="password_confirmation" placeholder="Re-enter password"
                                        required
                                        class="w-full h-9 pl-10 pr-3 text-sm
                              bg-white dark:bg-neutral-900
                              text-neutral-900 dark:text-neutral-100
                              border border-neutral-300 dark:border-neutral-700
                              focus:outline-none focus:border-primary-500
                              transition">
                                </div>
                            </div>

                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="pt-4 flex flex-col sm:flex-row gap-3">

                            <button type="submit"
                                class="w-full sm:w-auto h-9 px-5
                       inline-flex items-center justify-center gap-2
                       text-sm font-semibold
                       border
                       text-primary-600 border-primary-600/30 bg-primary-600/10
                       hover:bg-primary-600 hover:text-white
                       dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                       dark:hover:bg-primary-500 dark:hover:text-white
                       transition cursor-pointer">
                                <x-lucide-circle-plus class="w-4 h-4" />
                                Create User
                            </button>

                            <a href="{{ route('users.index') }}"
                                class="w-full sm:w-auto h-9 px-5
                  inline-flex items-center justify-center gap-2
                  text-sm font-semibold
                  border border-neutral-300 dark:border-neutral-700
                  text-neutral-700 dark:text-neutral-300
                  hover:bg-neutral-100 dark:hover:bg-neutral-800
                  transition cursor-pointer">
                                <x-lucide-arrow-left class="w-4 h-4" />
                                Cancel
                            </a>

                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

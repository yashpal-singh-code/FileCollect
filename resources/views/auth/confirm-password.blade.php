@extends('layouts.app')

@section('title', 'Confirm Password')
@section('description', 'Confirm your password before continuing.')

@section('content')

    <div class="max-w-xl mx-auto mt-16">

        <div class="bg-white dark:bg-neutral-900
                border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">

                <div class="flex items-center gap-3">
                    <x-lucide-lock class="w-6 h-6 text-primary-600 dark:text-primary-400" />

                    <div>
                        <h1 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                            Confirm Password
                        </h1>

                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                            Please confirm your password before continuing.
                        </p>
                    </div>
                </div>

            </div>


            <div class="p-6">

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf


                    {{-- PASSWORD --}}
                    <div>

                        <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-2">
                            Password
                        </label>

                        <div class="relative">

                            <x-lucide-key class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />

                            <input type="password" name="password" required autofocus placeholder="Enter your password"
                                class="w-full h-10 pl-10 pr-3 text-sm
                                   bg-white dark:bg-neutral-900
                                   text-neutral-900 dark:text-neutral-100
                                   placeholder-neutral-400
                                   border border-neutral-300 dark:border-neutral-700
                                   focus:outline-none focus:border-primary-500
                                   transition">

                        </div>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="flex items-center gap-3 pt-2">

                        <button type="submit"
                            class="h-9 px-4 inline-flex items-center gap-2
                               text-sm font-semibold
                               border
                               text-primary-600 border-primary-600/30 bg-primary-600/10
                               hover:bg-primary-600 hover:text-white
                               dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                               dark:hover:bg-primary-500 dark:hover:text-white
                               transition cursor-pointer">

                            <x-lucide-check class="w-4 h-4" />
                            Confirm Password

                        </button>

                        <a href="{{ url()->previous() }}"
                            class="h-9 px-4 inline-flex items-center gap-2
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

@endsection

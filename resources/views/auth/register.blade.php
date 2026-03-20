@extends('layouts.guest')

@section('title', 'Create Account – FileCollect')

@section('guest')

    {{-- FORM CARD --}}
    <div class="w-full sm:max-w-md mx-auto border border-gray-200 bg-white">

        {{-- Top Line --}}
        <div class="h-0.5 bg-linear-to-r from-blue-600 via-blue-500 to-blue-600"></div>

        <div class="px-4 sm:px-6 py-5">

            {{-- Header --}}
            <div class="mb-5 text-center">
                <h1 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Create your account
                </h1>
                <p class="text-sm text-gray-500">
                    Get started with FileCollect
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="plan" value="{{ $planSlug ?? 'free' }}">
                <input type="hidden" name="billing" value="{{ $billing ?? 'monthly' }}">

                {{-- Name --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">
                            First Name <span class="text-red-600">*</span>
                        </label>

                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name"
                            class="w-full h-10 sm:h-9 px-3 border text-sm border-gray-300
                                   focus:outline-none focus:border-blue-600"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">
                            Last Name <span class="text-red-600">*</span>
                        </label>

                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name"
                            class="w-full h-10 sm:h-9 px-3 border text-sm border-gray-300
                                   focus:outline-none focus:border-blue-600"
                            required>
                    </div>

                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm text-gray-700 mb-1">
                        Phone <span class="text-red-600">*</span>
                    </label>

                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210"
                        class="w-full h-10 sm:h-9 px-3 border text-sm border-gray-300
                               focus:outline-none focus:border-blue-600"
                        required>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm text-gray-700 mb-1">
                        Email <span class="text-red-600">*</span>
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                        class="w-full h-10 sm:h-9 px-3 border text-sm border-gray-300
                               focus:outline-none focus:border-blue-600"
                        required>
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">
                            Password <span class="text-red-600">*</span>
                        </label>

                        <input type="password" name="password" placeholder="Create password"
                            class="w-full h-10 sm:h-9 px-3 border text-sm border-gray-300
                                   focus:outline-none focus:border-blue-600"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">
                            Confirm Password <span class="text-red-600">*</span>
                        </label>

                        <input type="password" name="password_confirmation" placeholder="Confirm password"
                            class="w-full h-10 sm:h-9 px-3 border text-sm border-gray-300
                                   focus:outline-none focus:border-blue-600"
                            required>
                    </div>

                </div>

                {{-- Terms --}}
                <div class="flex items-start gap-2 text-sm">
                    <input type="checkbox" name="terms" class="mt-1 border-gray-300 text-blue-600 cursor-pointer"
                        required>

                    <p class="text-gray-600">
                        I agree to the
                        <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:underline">Terms</a>
                        and
                        <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:underline">Privacy Policy</a>
                        <span class="text-red-600">*</span>
                    </p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full h-10 sm:h-9 bg-blue-600 text-white text-sm font-medium
                           hover:bg-blue-700 transition cursor-pointer">
                    Create Account
                </button>

            </form>

            {{-- Login --}}
            <div class="mt-6 text-center text-sm text-gray-600">
                Already have an account?

                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                    Login
                </a>
            </div>

        </div>
    </div>

@endsection

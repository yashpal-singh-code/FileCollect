@extends('layouts.guest')

@section('title', 'Reset Password – FileCollect')

@section('guest')

    {{-- FORM CARD --}}
    <div class="w-full sm:max-w-md mx-auto border border-gray-200 bg-white">

        {{-- Top Line --}}
        <div class="h-0.5 bg-linear-to-r from-blue-600 via-blue-500 to-blue-600"></div>

        <div class="px-4 sm:px-6 py-5">

            {{-- Heading --}}
            <div class="mb-5 text-center">

                <h1 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Reset password
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Choose a new password for your account
                </p>

            </div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-4 border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4" x-data="{ showPassword: false, showConfirm: false }">

                @csrf

                {{-- Token --}}
                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                {{-- Email --}}
                <div>

                    <label class="block text-sm text-gray-700 mb-1">
                        Email address
                    </label>

                    <input type="email" value="{{ request('email') }}" readonly
                        class="w-full h-10 sm:h-9 px-3 border border-gray-300 bg-gray-100 text-sm cursor-not-allowed">

                    <input type="hidden" name="email" value="{{ request('email') }}">

                </div>

                {{-- New Password --}}
                <div>

                    <label class="block text-sm text-gray-700 mb-1">
                        New password
                    </label>

                    <div class="relative">

                        <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Create password"
                            required
                            class="w-full h-10 sm:h-9 px-3 pr-12 border border-gray-300 text-sm
                                   focus:outline-none focus:border-blue-600">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-2 flex items-center
                                   text-xs text-gray-500 hover:text-blue-600 cursor-pointer">

                            <span x-text="showPassword ? 'Hide' : 'Show'"></span>

                        </button>

                    </div>

                </div>

                {{-- Confirm Password --}}
                <div>

                    <label class="block text-sm text-gray-700 mb-1">
                        Confirm password
                    </label>

                    <div class="relative">

                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                            placeholder="Confirm password" required
                            class="w-full h-10 sm:h-9 px-3 pr-12 border border-gray-300 text-sm
                                   focus:outline-none focus:border-blue-600">

                        <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 right-2 flex items-center
                                   text-xs text-gray-500 hover:text-blue-600 cursor-pointer">

                            <span x-text="showConfirm ? 'Hide' : 'Show'"></span>

                        </button>

                    </div>

                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full h-10 sm:h-9 bg-blue-600 text-white text-sm font-medium
                           hover:bg-blue-700 transition cursor-pointer">

                    Reset Password

                </button>

            </form>

        </div>
    </div>

@endsection

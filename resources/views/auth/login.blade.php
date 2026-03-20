@extends('layouts.guest')

@section('title', 'Login – FileCollect')

@section('guest')

    {{-- FORM CARD --}}
    <div class="w-full sm:max-w-md mx-auto border border-gray-200 bg-white">

        {{-- Sharp Top Line --}}
        <div class="h-0.5 bg-linear-to-r from-blue-600 via-blue-500 to-blue-600"></div>
        <div class="px-4 sm:px-6 py-5">

            {{-- Form Heading --}}
            <div class="mb-5 text-center">
                <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Welcome back</h1>
                <p class="text-sm text-gray-500">Log in to your account to continue</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm text-gray-700 mb-1">
                        Email address
                    </label>

                    <input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}"
                        class="w-full h-10 sm:h-9 px-3 border border-gray-300 text-sm
                               focus:outline-none focus:border-blue-600 focus:ring-0"
                        required autofocus>
                </div>

                {{-- Password --}}
                <div>

                    <div class="flex justify-between items-center mb-1">
                        <label class="text-sm text-gray-700">
                            Password
                        </label>

                        <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full h-10 sm:h-9 px-3 border border-gray-300 text-sm
                               focus:outline-none focus:border-blue-600 focus:ring-0"
                        required>
                </div>

                {{-- Remember --}}
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember"
                        class="border-gray-300 text-blue-600 focus:ring-0">

                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Remember me
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full h-10 sm:h-9 bg-blue-600 text-white text-sm font-medium
                           hover:bg-blue-700 transition cursor-pointer">

                    Log in

                </button>

            </form>

            {{-- Register --}}
            <div class="mt-6 text-center text-sm text-gray-600">
                New to FileCollect?

                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                    Create an account
                </a>
            </div>

        </div>
    </div>

@endsection

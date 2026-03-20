@extends('layouts.guest')

@section('title', 'Forgot Password – FileCollect')

@section('guest')

    {{-- FORM CARD --}}
    <div class="w-full sm:max-w-md mx-auto border border-gray-200 bg-white">

        {{-- Top Line --}}
        <div class="h-0.5 bg-linear-to-r from-blue-600 via-blue-500 to-blue-600"></div>

        <div class="px-4 sm:px-6 py-5">

            {{-- Heading --}}
            <div class="mb-5 text-center">

                <h1 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Forgot your password?
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Enter your email and we’ll send you a reset link
                </p>

            </div>

            {{-- Success --}}
            @if (session('status'))
                <div class="mb-4 border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-4 border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>

                    <label class="block text-sm text-gray-700 mb-1">
                        Email address
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required
                        autofocus
                        class="w-full h-10 sm:h-9 px-3 border border-gray-300 text-sm
                               focus:outline-none focus:border-blue-600">

                    @error('email')
                        <p class="text-xs text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full h-10 sm:h-9 bg-blue-600 text-white text-sm font-medium
                           hover:bg-blue-700 transition cursor-pointer">

                    Send Reset Link

                </button>

            </form>

            {{-- Footer --}}
            <div class="mt-6 text-center text-sm text-gray-600">

                Remembered your password?

                <a href="{{ route('login') }}" class="text-blue-600 hover:underline cursor-pointer">
                    Back to login
                </a>

            </div>

        </div>
    </div>

@endsection

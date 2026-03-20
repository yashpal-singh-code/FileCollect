@extends('layouts.guest')

@section('title', 'Verify Email – FileCollect')

@section('guest')

    {{-- FORM CARD --}}
    <div class="w-full sm:max-w-md mx-auto border border-gray-200 bg-white">

        {{-- Top Line --}}
        <div class="h-0.5 bg-linear-to-r from-blue-600 via-blue-500 to-blue-600"></div>

        <div class="px-4 sm:px-6 py-6 text-center space-y-5">

            {{-- Icon --}}
            <div class="flex justify-center">
                <div class="h-10 w-10 flex items-center justify-center border border-blue-200">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 0l-4-4m4 4l-4 4" />
                    </svg>
                </div>
            </div>

            {{-- Heading --}}
            <div>
                <h1 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Verify your email address
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    We’ve sent a verification link to your email.
                    Please verify to continue using <strong>FileCollect</strong>.
                </p>
            </div>

            {{-- Success --}}
            @if (session('status') == 'verification-link-sent')
                <div class="border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                    A new verification link has been sent to your email.
                </div>
            @endif

            {{-- Resend --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button type="submit"
                    class="w-full h-10 sm:h-9 bg-blue-600 text-white text-sm font-medium
                           hover:bg-blue-700 transition cursor-pointer">

                    Resend verification email

                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 text-xs text-gray-400">
                <span class="flex-1 border-t border-gray-200"></span>
                <span>or</span>
                <span class="flex-1 border-t border-gray-200"></span>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 hover:underline cursor-pointer">
                    Logout and try another email
                </button>
            </form>

        </div>
    </div>

    {{-- Footer Hint --}}
    <p class="text-xs text-center text-gray-400 mt-4">
        Didn’t receive the email? Check your spam folder.
    </p>

@endsection

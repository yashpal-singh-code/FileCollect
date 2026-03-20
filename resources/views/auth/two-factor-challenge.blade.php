@extends('layouts.guest')

@section('title', 'Two-Factor Authentication – FileCollect')

@section('guest')

    {{-- Brand Header --}}
    <div class="flex flex-col items-center mb-8">
        <a href="/" class="flex items-center gap-2 cursor-pointer">
            <span class="text-2xl font-bold text-blue-600">FileCollect</span>
        </a>

        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-medium">
            Secure Client Document Collection
        </p>
    </div>


    {{-- Heading --}}
    <div class="mb-6 text-center">
        <h1 class="text-xl font-semibold text-gray-900">
            Two-Factor Authentication
        </h1>

        <p class="text-sm text-gray-500">
            Enter the verification code from your authenticator app.
        </p>
    </div>


    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 px-3 py-2">
            {{ $errors->first() }}
        </div>
    @endif


    {{-- OTP Form --}}
    <form method="POST" action="{{ url('/two-factor-challenge') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm text-gray-700 mb-1">
                Authentication Code
            </label>

            <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus
                placeholder="123456"
                class="w-full h-9 px-3 border border-gray-300 text-sm
                       focus:outline-none focus:border-blue-500"
                required>
        </div>


        <button type="submit"
            class="w-full h-9 bg-blue-600 text-white text-sm font-medium
                   hover:bg-blue-700 transition cursor-pointer">

            Verify Code

        </button>

    </form>


    {{-- Divider --}}
    <div class="flex items-center my-6">
        <div class="flex-1 border-t border-gray-200"></div>
        <span class="px-3 text-xs text-gray-400 uppercase tracking-wider">
            Or
        </span>
        <div class="flex-1 border-t border-gray-200"></div>
    </div>


    {{-- Recovery Code --}}
    <form method="POST" action="{{ url('/two-factor-challenge') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm text-gray-700 mb-1">
                Recovery Code
            </label>

            <input type="text" name="recovery_code" placeholder="Enter recovery code"
                class="w-full h-9 px-3 border border-gray-300 text-sm
                       focus:outline-none focus:border-blue-500">
        </div>


        <button type="submit"
            class="w-full h-9 border border-gray-300 text-sm font-medium
                   hover:bg-gray-100 transition cursor-pointer">

            Use Recovery Code

        </button>

    </form>

    {{-- Back to Login --}}
    <div class="mt-6 text-center text-sm text-gray-600">

        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
            ← Back to login
        </a>

    </div>
@endsection

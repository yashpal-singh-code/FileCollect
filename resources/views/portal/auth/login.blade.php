@extends('layouts.guest')

@section('title', 'Client Portal Login')

@section('guest')

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border">

        <h1 class="text-2xl font-bold mb-2">
            Secure Client Portal
        </h1>

        <p class="text-gray-500 mb-6">
            Login to access your document request and securely upload files.
        </p>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('portal.login', $token) }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror"
                    required autofocus>
            </div>

            <!-- Password -->
            <div>
                <input type="password" name="password" placeholder="Password"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl hover:bg-blue-600 transition">
                Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Need to activate your account?
            <a href="{{ route('portal.activate', $token) }}" class="text-blue-600 hover:underline font-medium">
                Set Password
            </a>
        </div>

    </div>

@endsection

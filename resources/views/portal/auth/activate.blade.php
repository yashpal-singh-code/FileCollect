@extends('layouts.guest')

@section('title', 'Activate Client Portal')

@section('guest')

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border">

        <h1 class="text-2xl font-bold mb-2">
            Activate Your Secure Portal
        </h1>

        <p class="text-gray-500 mb-6">
            Create a password to access your client dashboard and track documents.
        </p>

        <form method="POST" action="{{ route('portal.activate', $token) }}" class="space-y-4">
            @csrf

            <input type="password" name="password" placeholder="Create Password" class="w-full border rounded-lg px-3 py-2"
                required>

            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                class="w-full border rounded-lg px-3 py-2" required>

            <button class="w-full bg-slate-900 text-white py-3 rounded-xl hover:bg-blue-600 transition">
                Activate Portal
            </button>

        </form>

    </div>

@endsection

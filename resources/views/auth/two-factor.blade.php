@extends('layouts.app')

@section('title', 'Two-Factor Authentication – FileCollect')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6">

        {{-- HEADER --}}
        <div class="mb-6">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                Two-Factor Authentication
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Add an extra security layer to protect your account.
            </p>
        </div>


        {{-- CARD --}}
        <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 p-5 space-y-6">

            {{-- 2FA NOT ENABLED --}}
            @if (!auth()->user()->two_factor_secret)

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Protect your account using two-factor authentication.
                    You will be asked for an authentication code during login.
                </p>

                <form method="POST" action="/user/two-factor-authentication">
                    @csrf

                    <button type="submit"
                        class="h-9 px-4 inline-flex items-center justify-center gap-1.5
                    text-xs font-medium
                    border
                    text-primary-600 border-primary-600/30 bg-primary-600/10
                    hover:bg-primary-600 hover:text-white
                    dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                    dark:hover:bg-primary-500 dark:hover:text-white
                    transition cursor-pointer">

                        Enable Two-Factor Authentication
                    </button>

                </form>
            @else
                <div class="text-green-600 dark:text-green-400 text-sm">
                    Two-factor authentication is enabled.
                </div>


                {{-- QR SETUP --}}
                @if (!auth()->user()->two_factor_confirmed_at)
                    <div class="space-y-4">

                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Scan the QR code using your authenticator app.
                        </p>

                        <div class="flex justify-center bg-white p-6 border border-gray-200 dark:border-neutral-700">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>


                        {{-- OTP INPUT --}}
                        <form method="POST" action="/user/confirmed-two-factor-authentication" class="space-y-3">

                            @csrf

                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">
                                    Authentication Code
                                </label>

                                <input type="text" name="code" inputmode="numeric"
                                    placeholder="Enter authentication code" required
                                    class="w-full h-9 px-3 border text-sm
                                focus:outline-none
                                border-gray-300 focus:border-blue-500
                                dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                            </div>

                            <button type="submit"
                                class="w-full sm:w-auto h-9 px-4 inline-flex items-center justify-center gap-1.5
                            text-xs font-medium
                            border
                            text-primary-600 border-primary-600/30 bg-primary-600/10
                            hover:bg-primary-600 hover:text-white
                            dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                            dark:hover:bg-primary-500 dark:hover:text-white
                            transition cursor-pointer">

                                Confirm Authentication
                            </button>

                        </form>

                    </div>
                @endif


                {{-- RECOVERY CODES --}}
                @if (auth()->user()->two_factor_confirmed_at)

                    <div class="space-y-4">

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                Recovery Codes
                            </h3>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Store these codes safely. They can be used if you lose access to your authenticator.
                            </p>
                        </div>


                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-2
                        bg-gray-100 dark:bg-neutral-800
                        border border-gray-200 dark:border-neutral-700
                        p-4">

                            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes)) as $code)
                                <div
                                    class="font-mono text-xs text-gray-800 dark:text-gray-200
                                bg-white dark:bg-neutral-900
                                px-3 py-2 border border-gray-200 dark:border-neutral-700">

                                    {{ $code }}

                                </div>
                            @endforeach

                        </div>


                        {{-- REGENERATE --}}
                        <form method="POST" action="/user/two-factor-recovery-codes">
                            @csrf

                            <button type="submit"
                                class="h-9 px-4 inline-flex items-center justify-center gap-1.5
                            text-xs font-medium
                            border
                            text-yellow-600 border-yellow-600/30 bg-yellow-600/10
                            hover:bg-yellow-600 hover:text-white
                            dark:text-yellow-400 dark:border-yellow-400/40 dark:bg-yellow-400/10
                            dark:hover:bg-yellow-500 dark:hover:text-white
                            transition cursor-pointer">

                                Regenerate Codes
                            </button>

                        </form>

                    </div>

                @endif


                {{-- DISABLE --}}
                <form method="POST" action="/user/two-factor-authentication">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="h-9 px-4 inline-flex items-center justify-center gap-1.5
                    text-xs font-medium
                    border
                    text-red-600 border-red-600/30 bg-red-600/10
                    hover:bg-red-600 hover:text-white
                    dark:text-red-400 dark:border-red-400/40 dark:bg-red-400/10
                    dark:hover:bg-red-500 dark:hover:text-white
                    transition cursor-pointer">

                        Disable Two-Factor Authentication
                    </button>

                </form>

            @endif

        </div>


    </div>

@endsection

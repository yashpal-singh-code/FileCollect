@extends('layouts.guest')

@section('title', 'Too Many Requests – FileCollect')

@section('guest')

    <div class="w-full max-w-sm mx-auto">

        {{-- Brand Header --}}
        <div class="flex flex-col items-center mb-8 text-center">

            <a href="/" class="flex items-center gap-2 cursor-pointer">
                <span class="text-2xl font-bold text-blue-600">FileCollect</span>
            </a>

            <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-medium">
                Secure Client Document Collection
            </p>

        </div>


        {{-- Warning --}}
        <div class="text-center">

            <div class="mb-4 text-red-600">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0
                           2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46
                           0L3.34 16c-.77 1.33.19 3 1.73 3z" />

                </svg>

            </div>


            <h1 class="text-xl font-semibold text-gray-900 mb-2">
                Too Many Login Attempts
            </h1>


            <p class="text-sm text-gray-500 mb-6">

                For your security, access has been temporarily restricted.

                <br>

                Try again in

                <span id="countdown" class="font-semibold text-red-600 text-base" aria-live="polite">

                    {{ $seconds ?? 60 }}

                </span>

                seconds.

            </p>


            <a href="{{ route('login') }}"
                class="inline-block h-9 px-5 leading-9 bg-blue-600 text-white text-sm font-medium
                   hover:bg-blue-700 transition cursor-pointer">

                Back to Login

            </a>

        </div>

    </div>


    {{-- Countdown Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            let seconds = {{ $seconds ?? 60 }};

            const countdown = document.getElementById('countdown');

            if (!countdown) return;

            const timer = setInterval(() => {

                seconds--;

                if (seconds <= 0) {

                    clearInterval(timer);

                    window.location.href = "{{ route('login') }}";

                    return;

                }

                countdown.innerText = seconds;

                if (seconds <= 10) {

                    countdown.classList.remove('text-red-600');
                    countdown.classList.add('text-red-700');

                }

            }, 1000);

        });
    </script>

@endsection

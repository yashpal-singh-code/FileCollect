<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>
    @hasSection('title')
        @yield('title') | FileCollect
    @else
        FileCollect – Collect, Track & Complete Client Documents Faster
    @endif
</title>

<meta name="description" content="@yield('description', 'FileCollect is a secure client document collection platform for agencies and businesses.')">

<meta name="keywords" content="@yield('keywords', 'file collection software, client document collection, secure upload')">

<meta name="author" content="FileCollect">
<meta name="robots" content="index, follow">

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Theme Color --}}
<meta name="theme-color" content="#2563eb">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="FileCollect">

<meta property="og:title"
    content="@hasSection('title')
@yield('title') | FileCollect
@else
FileCollect – Secure Client Document Collection
@endif">

<meta property="og:description" content="@yield('description', 'Securely collect, track, and manage client documents with FileCollect.')">

<meta property="og:url" content="{{ url()->current() }}">

{{-- Social Preview Image --}}
<meta property="og:image" content="{{ asset('img/logo.svg') }}">
<meta name="twitter:image" content="{{ asset('img/logo.svg') }}">

<meta name="twitter:card" content="summary_large_image">

<meta name="twitter:title" content="@hasSection('title')
@yield('title') | FileCollect
@else
FileCollect
@endif">

<meta name="twitter:description" content="@yield('description', 'Secure client document collection platform.')">

<!-- FileCollect Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
<link rel="shortcut icon" href="{{ asset('img/favicon.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('img/favicon.svg') }}">

<link rel="manifest" href="{{ asset('manifest.json') }}">

<script>
    (() => {
        const theme = localStorage.getItem("theme") ?? "system";
        const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

        if (theme === "dark" || (theme === "system" && prefersDark)) {
            document.documentElement.classList.add("dark");
        }
    })();
</script>

@stack('styles')

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en">

<head>

    @include('layouts.head')

</head>
</head>

<body class="bg-slate-50">

    @include('website.layouts.header')

    @yield('website')

    @include('website.layouts.footer')

</body>

</html>

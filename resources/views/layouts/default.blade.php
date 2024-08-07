<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>Nvnhan's Wallet</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield('styles')
</head>

<body>
    <div class="d-flex w-100 {{ request()->route()->getName() !== 'index' ? 'mb-5' : '' }}">
        @yield('content')
    </div>

    @if(request()->route()->getName() !== 'index')
    @include('components.footer')
    @endif

    @yield('scripts')
</body>

</html>

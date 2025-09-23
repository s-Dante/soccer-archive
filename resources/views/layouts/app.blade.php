<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Soccer Archive')</title>
    <meta name="description" content="@yield('meta_description', 'Explora Soccer Archive')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    
    @stack('head')
</head>

<body class="bg-neutral-800 text-white relative"> {{-- Cambié el color de fondo para que coincida --}}
    @stack('underlay')

    {{-- Header siempre encima del fondo y del main --}}
    <div class="relative z-40">
        <x-header />
    </div>

    {{-- Contenido principal (encima del underlay, debajo del header si este es fixed) --}}
    <main class="relative z-10">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
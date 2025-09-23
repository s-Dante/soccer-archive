<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Soccer Archive</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-800 text-white">
    <x-header/>

    <main class="isolate w-full grid place-items-center overflow-hidden px-4 py-8">

        {{-- FONDO: Bolas de balones --}}
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
            <img src="{{ asset('images/MundialBalls/1930.png') }}" alt="" class="absolute w-[450px] -left-20 -top-16 opacity-40 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[15deg]">
            <img src="{{ asset('images/MundialBalls/1954.png') }}" alt="" class="absolute w-[320px] right-16 top-16 opacity-40 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[-15deg]">
            <img src="{{ asset('images/MundialBalls/1938.png') }}" alt="" class="absolute w-[350px] right-1/3 bottom-10 opacity-40 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[33deg]">
            <img src="{{ asset('images/MundialBalls/1962.png') }}" alt="" class="absolute w-[300px] left-24 bottom-40 opacity-40 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[35deg]">
        </div>

        {{-- TARJETA (slot) --}}
        <div class="glass w-full max-w-md p-8 text-neutral-100">
            @yield('content')
        </div>
    </main>
</body>
</html>
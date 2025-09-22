@extends('layouts.app')

@section('title', 'Login | Soccer Archive')
@section('body_class', 'min-h-screen bg-neutral-800') {{-- fondo oscuro global --}}

@push('head')
    <style>
        /* Tarjeta glass con “manchas” de luz */
        .glass {
            position: relative;
            background: rgba(255, 255, 255, .05);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: 0 30px 80px rgba(0, 0, 0, .6);
            backdrop-filter: blur(22px);
        }
    </style>

    
@endpush

@section('content')
    <section class="isolate min-h-[calc(100vh-6rem)] w-full grid place-items-center overflow-hidden px-4">

        {{-- FONDO: bolas “neumórficas” --}}
        <div aria-hidden class="pointer-events-none absolute inset-0 -z-10">
            {{-- Si quieres una imagen (balón vintage) en esquina inferior derecha: --}}
            <img src="{{ asset('images/MundialBalls/1930.png') }}" alt=""
                class="absolute w-[450px] -left-20 -top-16 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[15deg]">
            <img src="{{ asset('images/MundialBalls/1934.png') }}" alt=""
                class="absolute w-[310px] left-1/3 top-16 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[-25deg]">
            <img src="{{ asset('images/MundialBalls/1950.png') }}" alt=""
                class="absolute w-[270px] left-24 bottom-40 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[35deg]">
            <img src="{{ asset('images/MundialBalls/1954.png') }}" alt=""
                class="absolute w-[320px] right-16 top-16 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[-15deg]">
            <img src="{{ asset('images/MundialBalls/1958.png') }}" alt=""
                class="absolute w-[410px] right-155 bottom-10 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[65deg]">
            <img src="{{ asset('images/MundialBalls/1962.png') }}" alt=""
                class="absolute w-[360px] left-130 bottom-45 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[2deg]">
            <img src="{{ asset('images/MundialBalls/1966.png') }}" alt=""
                class="absolute w-[300px] -right-10 bottom-31 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[33deg]">
            <img src="{{ asset('images/MundialBalls/1970.png') }}" alt=""
                class="absolute w-[350px] right-125 top-31 drop-shadow-[0_30px_60px_rgba(0,0,0,.6)] rotate-[33deg]">
        </div>


        {{-- TARJETA LOGIN (glassmorphism) --}}
        <div class="glass w-full max-w-md p-8 text-neutral-100">

            <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow">Login</h1>

            {{-- Social --}}
            <div class="flex gap-3 mb-5">
                <button type="button"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-md bg-white/90 text-neutral-800 px-3 py-2 font-medium hover:bg-white transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="" class="w-4 h-4">
                    Google
                </button>
                <button type="button"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-md bg-white/90 text-neutral-900 px-3 py-2 font-medium hover:bg-white transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M16.365 1.43c0 1.14-.42 2.056-1.256 2.746-.84.694-1.76 1.095-2.76 1.2-.056-.1-.085-.225-.085-.375 0-1.094.42-1.99 1.26-2.69.84-.705 1.77-1.12 2.78-1.24.04.12.06.24.06.36zm4.24 16.01c-.37.86-.807 1.65-1.31 2.36-.69.98-1.25 1.66-1.68 2.03-.67.61-1.39.93-2.17.96-.55 0-1.21-.16-1.98-.48-.77-.32-1.48-.48-2.13-.48-.69 0-1.41.16-2.17.48-.77.32-1.38.49-1.84.5-.75.03-1.49-.29-2.2-.95-.47-.42-1.06-1.12-1.77-2.1-.76-1.03-1.39-2.22-1.9-3.56-.53-1.42-.8-2.8-.8-4.14 0-1.53.33-2.85.98-3.98a6.9 6.9 0 0 1 2.44-2.54 6.6 6.6 0 0 1 3.37-.95c.66 0 1.52.19 2.57.58 1.05.39 1.72.58 2 .58.2 0 .9-.22 2.09-.66 1.12-.4 2.06-.56 2.82-.48 2.08.17 3.64.97 4.68 2.4-1.86 1.12-2.8 2.7-2.8 4.74 0 1.59.6 2.92 1.8 3.99.54.5 1.14.89 1.8 1.17-.15.42-.33.82-.53 1.2z" />
                    </svg>
                    Apple ID
                </button>
            </div>

            <p class="text-sm text-neutral-300 mb-2">o continúa con correo:</p>

            <form action="{{ route('home') }}" method="POST" class="space-y-3">
                @csrf
                <input type="email" name="email" placeholder="Correo"
                    class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="password" name="password" placeholder="Contraseña"
                    class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
                    Entrar
                </button>
            </form>

            <div class="mt-4 text-sm text-neutral-300">
                <a href="#" class="hover:underline">¿Has olvidado tu contraseña?</a>
                <div class="mt-1">¿Aún no tienes una cuenta?
                    <a href="#" class="font-semibold hover:underline">Regístrate</a>
                </div>
            </div>
        </div>
    </section>
@endsection

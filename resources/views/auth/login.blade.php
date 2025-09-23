@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow">Login</h1>

    {{-- Social Login --}}
    <div class="flex gap-3 mb-5">
        {{-- NOTA: Estos botones aún no tendrán funcionalidad --}}
        <button type="button" class="flex-1 inline-flex items-center justify-center gap-2 rounded-md bg-white/90 text-neutral-800 px-3 py-2 font-medium hover:bg-white transition cursor-not-allowed" >
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4">
            Google
        </button>
        {{-- Aquí iría el de Facebook cuando lo integremos --}}
    </div>

    <p class="text-sm text-neutral-300 mb-2">o continúa con correo:</p>

    <form action="{{-- route('auth.login.auth') --}}" method="POST" class="space-y-3">
        @csrf
        <input type="email" name="email" placeholder="Correo o Usuario" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="password" name="password" placeholder="Contraseña" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Entrar
        </button>
    </form>

    <div class="mt-4 text-sm text-neutral-300">
        <a href="{{ route('auth.forgot') }}" class="hover:underline">¿Has olvidado tu contraseña?</a>
        <div class="mt-1">¿Aún no tienes una cuenta?
            <a href="{{ route('auth.register') }}" class="font-semibold hover:underline">Regístrate</a>
        </div>
    </div>
@endsection
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow">Login</h1>

    {{-- 1. Mensaje de Éxito (si vienes del registro) --}}
    @if (session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- 2. ¡AQUÍ ESTÁ LA SECCIÓN CLAVE PARA MOSTRAR ERRORES! --}}
    @error('identifier')
        <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-4">
            {{ $message }}
        </div>
    @endError

    {{-- ... (tu social login) ... --}}

    <p class="text-sm text-neutral-300 mb-2">o continúa con correo:</p>

    <form action="{{ route('auth.login.auth') }}" method="POST" class="space-y-4">
        @csrf
        
        {{-- 3. Asegúrate de que el 'name' sea 'identifier' --}}
        <input type="text" name="identifier" placeholder="Correo o Usuario" value="{{ old('identifier') }}" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <input type="password" name="password" placeholder="Contraseña" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-3 transition">
            Entrar
        </button>
    </form>

    <div class="mt-4 text-sm text-neutral-300">
        <a href="{{ route('auth.forgot.form') }}" class="hover:underline">¿Has olvidado tu contraseña?</a>
        <div class="mt-1">¿Aún no tienes una cuenta?
            <a href="{{ route('auth.register') }}" class="font-semibold hover:underline">Regístrate</a>
        </div>
    </div>
@endsection


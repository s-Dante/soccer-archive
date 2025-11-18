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

    <div class="flex items-center justify-between mt-6">
        <span class="w-1/5 border-b border-gray-600 lg:w-1/4"></span>
        <span class="text-xs text-center text-gray-400 uppercase">
            Inicia sesión con
        </span>
        <span class="w-1/5 border-b border-gray-600 lg:w-1/4"></span>
    </div>

    <a href="{{ route('auth.social.redirect', 'google') }}" 
    class="flex items-center justify-center w-full px-6 py-3 mt-4 text-white transition-colors duration-300 transform bg-red-600 rounded-lg hover:bg-red-500 focus:outline-none">
        <svg class="w-6 h-6 mx-2" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z"/>
        </svg>
        <span class="mx-2 text-sm font-medium">Google</span>
    </a>

    <a href="{{ route('auth.social.redirect', 'facebook') }}" 
    class="flex items-center justify-center w-full px-6 py-3 mt-4 text-white transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none">
        <svg class="w-6 h-6 mx-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
        </svg>
        <span class="mx-2 text-sm font-medium">Facebook</span>
    </a>

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


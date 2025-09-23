@extends('layouts.auth')

@section('title', 'Registro')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow">Sign In</h1>

    <form action="#" method="POST" class="space-y-3">
        @csrf
        <div class="flex gap-3">
            <input type="text" name="name" placeholder="Nombre(s)" required class="flex-1 w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
            <input type="text" name="last_name" placeholder="Apellidos" required class="flex-1 w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        </div>
        <input type="email" name="email" placeholder="Correo" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        <input type="password" name="password" placeholder="Contraseña" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Registrarse
        </button>
    </form>

    <div class="mt-4 text-sm text-neutral-300">
        ¿Ya tienes una cuenta? <a href="{{ route('auth.login') }}" class="font-semibold hover:underline">Inicia Sesión</a>
    </div>
@endsection
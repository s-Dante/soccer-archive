@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-4xl tracking-tight mb-2 drop-shadow text-center">Change Password</h1>
    <p class="text-neutral-300 text-center mb-6">Ingresa tu correo para enviarte un código de recuperación.</p>

    <form action="#" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Correo" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Enviar Código
        </button>
    </form>
@endsection
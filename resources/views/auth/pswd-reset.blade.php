@extends('layouts.auth')

@section('title', 'Nueva Contraseña')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow text-center">Nueva Contraseña</h1>

    <form action="#" method="POST" class="space-y-3">
        @csrf
        <input type="password" name="password" placeholder="Nueva Contraseña" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        <input type="password" name="password_confirmation" placeholder="Confirmar Nueva Contraseña" required class="w-full rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Guardar Cambios
        </button>
    </form>
@endsection
@extends('layouts.auth')

@section('title', 'Ingresar Código')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-2 drop-shadow text-center">Ingresa el Código</h1>
    <p class="text-neutral-300 text-center mb-6">Revisa tu bandeja de entrada.</p>

    <form action="#" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="token" placeholder="Código" required class="w-full text-center tracking-widest rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Cambiar Contraseña
        </button>
    </form>
@endsection
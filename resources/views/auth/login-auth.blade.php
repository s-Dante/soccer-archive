@extends('layouts.auth')

@section('title', 'Validar Cuenta')

@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-2 drop-shadow text-center">Valida tu Cuenta</h1>
    <p class="text-neutral-300 text-center mb-6">Ingresa el código que enviamos a tu correo.</p>

    <form action="#" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="token" placeholder="Código" required maxlength="6" class="w-full text-center text-2xl tracking-[.5em] rounded-md bg-white/90 text-neutral-900 px-3 py-2 shadow-inner placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Verificar
        </button>
    </form>
    <div class="mt-4 text-sm text-center">
        <button type="button" class="text-neutral-300 hover:underline">¿No recibiste el código? Reenviar</button>
    </div>
@endsection
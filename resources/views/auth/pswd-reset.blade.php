@extends('layouts.auth')
@section('title', 'Nueva Contraseña')
@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-6 drop-shadow text-center">Nueva Contraseña</h1>
    <p class="text-neutral-300 text-center mb-6">Ingresa tu nueva contraseña.</p>

    @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('auth.reset.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="password" name="password" placeholder="Nueva Contraseña" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="password" name="password_confirmation" placeholder="Confirmar Nueva Contraseña" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Guardar Cambios
        </button>
    </form>
@endsection


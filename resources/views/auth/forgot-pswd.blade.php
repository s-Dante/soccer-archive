@extends('layouts.auth')
@section('title', 'Recuperar Contraseña')
@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-4xl tracking-tight mb-2 drop-shadow text-center">Change Password</h1>
    <p class="text-neutral-300 text-center mb-6">Ingresa tu correo para enviarte un código.</p>

    {{-- Mostrar mensaje de éxito --}}
    @if (session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('auth.forgot.send') }}" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}" required class="w-full rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
            <p class="text-red-400 text-xs">{{ $message }}</p>
        @enderror
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Enviar Código
        </button>
    </form>
@endsection


@extends('layouts.auth')
@section('title', 'Ingresar Código')
@section('content')
    <h1 class="baloo-bhaijaan-2-extrabold font-extrabold text-5xl tracking-tight mb-2 drop-shadow text-center">Ingresa el Código</h1>
    
    @if (session('success'))
        <p class="text-green-400 text-center mb-6">{{ session('success') }}</p>
    @else
        <p class="text-neutral-300 text-center mb-6">Revisa tu bandeja de entrada.</p>
    @endif

    <form action="{{ route('auth.token.verify') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="token" placeholder="Código de 6 dígitos" required class="w-full text-center tracking-widest rounded-md bg-white/10 text-neutral-100 px-3 py-2 shadow-inner placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('token')
            <p class="text-red-400 text-xs text-center">{{ $message }}</p>
        @enderror
        
        <button type="submit" class="w-full rounded-md bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 transition">
            Verificar Código
        </button>
    </form>
@endsection


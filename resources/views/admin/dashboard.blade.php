@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-4xl font-bold mb-8">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Card de Publicaciones Pendientes --}}
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-2">Publicaciones Pendientes</h3>
            {{-- Mostramos el dato real --}}
            <p class="text-5xl font-bold text-yellow-400">{{ $stats->pending_publications ?? 0 }}</p>
            <a href="{{ route('admin.publications.index') }}" class="mt-4 inline-block text-blue-400 hover:underline">Revisar ahora &rarr;</a>
        </div>

        {{-- Card de Usuarios Registrados --}}
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-2">Usuarios Registrados</h3>
            {{-- Mostramos el dato real --}}
            <p class="text-5xl font-bold text-green-400">{{ $stats->total_users ?? 0 }}</p>
            <a href="{{ route('admin.users.index') }}" class="mt-4 inline-block text-blue-400 hover:underline">Gestionar usuarios &rarr;</a>
        </div>

        {{-- Card de Mundiales Creados --}}
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-2">Mundiales en la BD</h3>
            {{-- Mostramos el dato real --}}
            <p class="text-5xl font-bold text-purple-400">{{ $stats->total_world_cups ?? 0 }}</p>
            <a href="{{ route('admin.worldcups.index') }}" class="mt-4 inline-block text-blue-400 hover:underline">Ver mundiales &rarr;</a>
        </div>
    </div>
@endsection

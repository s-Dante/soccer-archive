@extends('layouts.admin')

@section('title', 'Revisar Publicación')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold">Revisar Publicación</h1>
        <a href="{{ route('admin.publications.index') }}" class="text-blue-400 hover:underline">&larr; Volver a la lista</a>
    </div>

    {{-- Área de Revisión --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Columna de Acciones (Botones de Admin) --}}
        <div class="md:col-span-1">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg sticky top-6">
                <h2 class="text-2xl font-semibold mb-4">Acciones de Moderación</h2>
                <p class="text-gray-400 mb-2">Publicación de: <span class="font-bold text-white">{{ $details->author_name }}</span></p>
                <p class="text-gray-400 mb-6">Estado actual: 
                    <span class="font-bold 
                        @if($details->status == 'accepted') text-green-400 
                        @elseif($details->status == 'rejected') text-orange-400
                        @else text-yellow-400 @endif">
                        {{ ucfirst($details->status) }}
                    </span>
                </p>

                <div class="space-y-4">
                    {{-- Formulario para APROBAR --}}
                    <form action="{{ route('admin.publications.updateStatus', $details->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 px-4 rounded-lg transition">
                            Aprobar
                        </button>
                    </form>

                    {{-- Formulario para RECHAZAR --}}
                    <form action="{{ route('admin.publications.updateStatus', $details->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-500 text-white font-bold py-3 px-4 rounded-lg transition">
                            Rechazar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Columna de Contenido (Llama al Componente) --}}
        <div class="md:col-span-2 flex justify-center">
            
            {{-- *** ¡AQUÍ ESTÁ LA MAGIA! *** --}}
            {{-- Usamos el componente que creamos y le pasamos los datos --}}
            <x-publication-card :details="$details" :media="$media" />
            
        </div>
        
    </div>
@endsection
@extends('layouts.admin')

@section('title', 'Añadir Nuevo Mundial')

@section('content')
    <h1 class="text-4xl font-bold mb-8">Añadir Nuevo Mundial</h1>

    {{-- Mostramos errores de validación del backend --}}
    @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Contenedor del formulario --}}
    <div class="max-w-2xl bg-gray-800 p-8 rounded-lg shadow-lg">
        <form action="{{ route('admin.worldcups.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- Año --}}
            <div>
                <label for="year" class="block mb-2 text-sm font-medium text-gray-300">Año del Mundial</label>
                <input type="number" id="year" name="year" value="{{ old('year') }}" placeholder="Ej: 1986" required 
                       class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
            </div>

            {{-- Sede --}}
            <div>
                <label for="host_country" class="block mb-2 text-sm font-medium text-gray-300">País Sede</label>
                <input type="text" id="host_country" name="host_country" value="{{ old('host_country') }}" placeholder="Ej: México" required
                       class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
            </div>

            {{-- Descripción --}}
            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-300">Descripción / Reseña</label>
                <textarea id="description" name="description" rows="4" placeholder="Escribe una breve reseña del mundial..." required
                          class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">{{ old('description') }}</textarea>
            </div>

            {{-- Imagen de Portada --}}
            <div>
                <label for="cover_image" class="block mb-2 text-sm font-medium text-gray-300">Imagen de Portada (Opcional)</label>
                <input type="file" id="cover_image" name="cover_image" 
                       class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
            </div>
            
            {{-- Imagen del Balón --}}
            <div>
                <label for="ball_image" class="block mb-2 text-sm font-medium text-gray-300">Imagen del Balón (Opcional)</S>
                <input type="file" id="ball_image" name="ball_image" 
                       class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-lg transition">
                Guardar Mundial
            </button>
        </form>
    </div>
@endsection

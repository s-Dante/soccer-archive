@extends('layouts.admin')

@section('title', 'Gestionar Categorías')

@section('content')
    <h1 class="text-4xl font-bold mb-8">Categorías de Publicación</h1>

    {{-- Mostrar mensajes de éxito --}}
    @if (session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Formulario para crear categoría --}}
        <div class="md:col-span-1 bg-gray-800 p-6 rounded-lg shadow-lg h-fit">
            <h2 class="text-2xl font-semibold mb-4">Nueva Categoría</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nombre de la categoría"
                           class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">Crear</button>
            </form>
        </div>

        {{-- Tabla de categorías existentes --}}
        <div class="md:col-span-2 bg-gray-800 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-4 p-6">Categorías Existentes</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-[400px] text-left">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="p-4">Nombre</th>
                            <th class="p-4">Fecha de Creación</th>
                            <th class="p-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                <td class="p-4 font-medium">{{ $category->name }}</td>
                                <td class="p-4 text-gray-400">{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }}</td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esto eliminará la categoría Y TODAS las publicaciones asociadas a ella.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 transition-colors" title="Borrar">
                                            Borrar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-400">
                                    No hay categorías creadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

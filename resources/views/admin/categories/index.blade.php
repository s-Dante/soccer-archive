@extends('layouts.admin')

@section('title', 'Gestionar Categorías')

@section('content')
    <h1 class="text-4xl font-bold mb-8">Categorías de Publicación</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Formulario para crear categoría --}}
        <div class="md:col-span-1 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Nueva Categoría</h2>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" placeholder="Nombre de la categoría" class="w-full bg-gray-700 rounded p-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">Crear</button>
            </form>
        </div>
        {{-- Tabla de categorías existentes --}}
        <div class="md:col-span-2 bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Categorías Existentes</h2>
            {{-- Aquí iría una tabla o lista con las categorías y botones de Editar/Borrar --}}
        </div>
    </div>
@endsection
@extends('layouts.admin')

@section('title', 'Gestionar Mundiales')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Mundiales</h1>
        <a href="{{-- route('admin.worldcups.create') --}}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">Añadir Mundial</a>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full text-left">
            {{-- Similar a la tabla de publicaciones, con columnas Año, Sede, Acciones (Editar, Borrar) --}}
        </table>
    </div>
@endsection
@extends('layouts.admin')

@section('title', 'Añadir Nuevo Mundial')

@section('content')
    <h1 class="text-4xl font-bold mb-8">Añadir Nuevo Mundial</h1>
    <div class="max-w-2xl bg-gray-800 p-8 rounded-lg shadow-lg">
        <form action="{{-- route('admin.worldcups.store') --}}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            {{-- Aquí irían los campos del formulario: Año, Sede, Descripción, Imagen de Portada, Imagen de Balón --}}
        </form>
    </div>
@endsection
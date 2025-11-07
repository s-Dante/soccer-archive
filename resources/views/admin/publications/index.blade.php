@extends('layouts.admin')

@section('title', 'Gestionar Publicaciones')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Gestionar Publicaciones</h1>
        {{-- (Aquí podríamos poner un filtro de "Pendientes" en el futuro) --}}
    </div>

    {{-- Mensajes de éxito o error --}}
    @if (session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-3 mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabla de publicaciones --}}
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full min-w-[900px] text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-4">Título</th>
                    <th class="p-4">Autor</th>
                    <th class="p-4">Mundial</th>
                    <th class="p-4">Categoría</th>
                    <th class="p-4">Fecha de Envío</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publications as $pub)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                        <td class="p-4 font-medium">{{ Str::limit($pub->title, 40) }}</td>
                        <td class="p-4 text-gray-400">{{ $pub->author_name }}</td>
                        <td class="p-4 text-gray-400">{{ $pub->world_cup_year }}</td>
                        <td class="p-4 text-gray-400">{{ $pub->category_name }}</td>
                        <td class="p-4 text-gray-400">{{ \Carbon\Carbon::parse($pub->published_at)->format('d/m/Y') }}</td>
                        <td class="p-4">
                            {{-- Lógica de Badges de Estado --}}
                            @if($pub->deleted_at)
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-400 bg-red-800/50">
                                    Eliminado
                                </span>
                            @elseif($pub->status == 'accepted')
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-400 bg-green-800/50">
                                    Aprobado
                                </span>
                            @elseif($pub->status == 'rejected')
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-orange-400 bg-orange-800/50">
                                    Rechazado
                                </span>
                            @else
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-400 bg-yellow-800/50">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4">
                                {{-- El botón de Revisar te lleva a la vista 'show' (Paso 6b) --}}
                                <a href="{{ route('admin.publications.show', $pub->id) }}" class="text-blue-400 hover:text-blue-300 transition-colors" title="Revisar">
                                    Revisar
                                </a>
                                
                                {{-- Formulario para Baja Lógica (DELETE) --}}
                                <form action="{{ route('admin.publications.destroy', $pub->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres dar de baja esta publicación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button typef="submit" class="text-red-500 hover:text-red-400 transition-colors" title="Dar de Baja">
                                        Dar de Baja
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-400">
                            No hay publicaciones para mostrar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
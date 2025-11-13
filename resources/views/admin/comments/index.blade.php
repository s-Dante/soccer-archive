@extends('layouts.admin')

@section('title', 'Gestionar Comentarios')

@section('content')
<div class="mb-6">
    <h1 class="text-4xl font-bold">Gestionar Comentarios</h1>
</div>

{{-- Alerta de éxito --}}
@if (session('success'))
    <div class="bg-green-600/50 border border-green-700 text-green-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

{{-- Tabla de Comentarios --}}
<div class="bg-gray-800 shadow-xl rounded-lg overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-700/50 border-b border-gray-700">
                <th class="px-6 py-4 font-semibold uppercase">ID</th>
                <th class="px-6 py-4 font-semibold uppercase">Comentario</th>
                <th class="px-6 py-4 font-semibold uppercase">Autor</th>
                <th class="px-6 py-4 font-semibold uppercase">Publicación</th>
                <th class="px-6 py-4 font-semibold uppercase">Fecha</th>
                <th class="px-6 py-4 font-semibold uppercase">Estado</th>
                <th class="px-6 py-4 font-semibold uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse ($comments as $comment)
                <tr class="hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">{{ $comment->id }}</td>
                    <td class="px-6 py-4">
                        <span class="text-gray-300">{{ Str::limit($comment->content, 70) }}</span>
                    </td>
                    <td class="px-6 py-4">{{ $comment->author_name }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.publications.show', $comment->publication_id) }}" 
                           class="text-blue-400 hover:underline"
                           title="{{ $comment->publication_title }}">
                            Ver (ID: {{ $comment->publication_id }})
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($comment->deleted_at)
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-800/60 text-red-200">
                                Baja
                            </span>
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-800/60 text-red-200">
                                (oculto)
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-800/60 text-green-200">
                                Visible
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if ($comment->deleted_at)
                            {{-- Botón RESTAURAR --}}
                            <form action="{{ route('admin.comments.restore', $comment->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type
="submit" class="text-green-400 hover:text-green-300 font-semibold">
                                    Restaurar
                                </button>
                            </form>
                        @else
                            {{-- Botón DAR DE BAJA --}}
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 font-semibold"
                                        onclick="return confirm('¿Estás seguro de que quieres dar de baja este comentario? Se ocultará para los usuarios.')">
                                    Dar de Baja
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center px-6 py-8 text-gray-400">
                        No se encontraron comentarios.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
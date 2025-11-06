@extends('layouts.admin')

@section('title', 'Gestionar Usuarios')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Usuarios Registrados</h1>
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

    <div class="bg-gray-800 rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full min-w-[700px] text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-4">Nombre Completo</th>
                    <th class="p-4">Usuario</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Rol</th>
                    <th class="p-4">Estatus</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                        <td class="p-4 font-medium">{{ $user->name }} {{ $user->last_name }}</td>
                        <td class="p-4 text-gray-400">{{ $user->username }}</td>
                        <td class="p-4 text-gray-400">{{ $user->email }}</td>
                        <td class="p-4">
                            @if($user->role == 'admin')
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-purple-400 bg-purple-800/50">
                                    {{ $user->role }}
                                </span>
                            @else
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-gray-400 bg-gray-600/50">
                                    {{ $user->role }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($user->deleted_at)
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-400 bg-red-800/50">
                                    Inactivo
                                </span>
                            @else
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-400 bg-green-800/50">
                                    Activo
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4">
                                @if($user->deleted_at)
                                    {{-- Botón para REACTIVAR (PATCH) --}}
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-400 hover:text-green-300 transition-colors" title="Reactivar">
                                            Reactivar
                                        </button>
                                    </form>
                                @else
                                    {{-- Botón para DAR DE BAJA (DELETE) --}}
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres dar de baja a este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 transition-colors" title="Dar de Baja">
                                            Dar de Baja
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-400">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
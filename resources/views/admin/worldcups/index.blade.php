@extends('layouts.admin')

@section('title', 'Gestionar Mundiales')

@section('content')
    
    {{-- Encabezado de la página con el botón de "Añadir" --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Mundiales</h1>
        <a href="{{ route('admin.worldcups.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg transition">
            Añadir Mundial
        </a>
    </div>

    {{-- Mensaje de éxito cuando se crea un mundial --}}
    @if (session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Contenedor de la tabla con scroll horizontal para móviles --}}
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full min-w-[600px] text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-4">Año</th>
                    <th class="p-4">Sede (País)</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aquí es donde se usa la variable $worldCups --}}
                @forelse ($worldCups as $wc)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                        <td class="p-4 font-medium">{{ $wc->year }}</td>
                        <td class="p-4">{{ $wc->host_country }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4">
                                {{-- Estos enlaces aún no tienen ruta, pero las crearemos --}}
                                <a href="#" class="text-blue-400 hover:text-blue-300 transition-colors" title="Editar">
                                    Editar
                                </a>
                                <form action="#" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type"submit" class="text-red-500 hover:text-red-400 transition-colors" title="Borrar">
                                        Borrar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- Esto se muestra si la tabla está vacía --}}
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-400">
                            Aún no se ha creado ningún mundial.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


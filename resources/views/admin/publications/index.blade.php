@extends('layouts.admin')

@section('title', 'Gestionar Publicaciones')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Publicaciones Pendientes</h1>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-lg">
        <table class="w-full min-w-[640px] text-left overflow-x-auto">
            <thead class="bg-gray-700">
                <tr>
                    <th class="p-4">Título</th>
                    <th class="p-4">Autor</th>
                    <th class="p-4">Mundial</th>
                    <th class="p-4">Fecha</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Fila de ejemplo 1 --}}
                <tr class="border-b border-gray-700">
                    <td class="p-4">La Mano de Dios</td>
                    <td class="p-4">usuario@ejemplo.com</td>
                    <td class="p-4">México 1986</td>
                    <td class="p-4">20/09/2025</td>
                    <td class="p-4 flex gap-2">
                        <button class="bg-green-600 hover:bg-green-500 text-white font-bold py-1 px-3 rounded">Aprobar</button>
                        <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-1 px-3 rounded">Rechazar</button>
                    </td>
                </tr>
                {{-- Fila de ejemplo 2 --}}
                <tr class="border-b border-gray-700">
                    <td class="p-4">El cabezazo de Zidane</td>
                    <td class="p-4">otro@ejemplo.com</td>
                    <td class="p-4">Alemania 2006</td>
                    <td class="p-4">19/09/2025</td>
                    <td class="p-4 flex gap-2">
                        <button class="bg-green-600 hover:bg-green-500 text-white font-bold py-1 px-3 rounded">Aprobar</button>
                        <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-1 px-3 rounded">Rechazar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Mi Perfil') {{-- Esto define el título de la página --}}

@section('body_class', 'bg-neutral-800 text-white') {{-- Asignamos la clase al body --}}

@section('content') {{-- Aquí comienza el contenido principal --}}
    <div class="container mx-auto px-4 py-8 text-white">
        
        {{-- SECCIÓN SUPERIOR DEL PERFIL --}}
        <div class="flex flex-col md:flex-row items-center gap-8 mb-12">
            {{-- FOTO DE PERFIL --}}
            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-600 shadow-lg">
                @auth
                    @if (Auth::user()->profile_photo)
                        <img class="w-full h-full object-cover" 
                             src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->profile_photo) }}" 
                             alt="Foto de perfil de {{ Auth::user()->name }}">
                    @else
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                            <span class="text-4xl text-gray-400">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                @endauth
            </div>
            
            {{-- INFORMACIÓN Y BOTONES --}}
            <div>
                @auth
                    <h1 class="text-4xl font-bold">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h1>
                    <p class="text-lg text-gray-400">{{ Auth::user()->email }}</p>
                @endauth
                <div class="mt-4 flex gap-4">
                    <a href="{{ route('user.contribute') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg transition">
                        Contribuir
                    </a>
                    <a href="{{ route('user.settings') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        {{-- SECCIÓN DE "MIS PUBLICACIONES" --}}
        <div>
            <h2 class="text-3xl font-bold border-b-2 border-gray-700 pb-2 mb-6">Mis Publicaciones</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Ejemplo de una tarjeta de publicación --}}
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Título de la Publicación</h3>
                        <p class="text-gray-400 text-sm mb-2">Mundial: México 1986</p>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-600 bg-yellow-200">
                            Pendiente de Aprobación
                        </span>
                    </div>
                </div>
                <p class="text-gray-400 col-span-full">Aún no has creado ninguna publicación.</p>
            </div>
        </div>
    </div>
@endsection {{-- Aquí termina el contenido principal --}}
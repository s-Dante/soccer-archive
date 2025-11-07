@extends('layouts.app') {{-- Usamos el layout principal --}}

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-12 text-white">

    {{-- Encabezado del Perfil (Información del Usuario) --}}
    <div class="flex flex-col md:flex-row items-center md:items-start text-center md:text-left gap-8 mb-12">
        {{-- Foto de Perfil (usamos la misma lógica del header) --}}
        @php
            $user = Auth::user();

            // Si quieres las iniciales de nombre y apellido:
            $initials = '';
            if ($user) {
                $parts = explode(' ', trim($user->name)); // separa por espacios
                $first = strtoupper(substr($parts[0] ?? '', 0, 1));
                $last = strtoupper(substr(end($parts) ?? '', 0, 1));
                $initials = $first . ($first !== $last ? $last : '');
            }
        @endphp

        @if ($user && $user->profile_photo)
            {{-- Foto real --}}
            <img class="h-32 w-32 md:h-40 md:w-40 rounded-full object-cover border-4 border-gray-700"
                src="data:image/jpeg;base64,{{ base64_encode($user->profile_photo) }}"
                alt="Foto de perfil">
        @else
            {{-- Círculo con inicial(es) --}}
            <div class="h-32 w-32 md:h-40 md:w-40 rounded-full bg-gray-700 border-4 border-gray-700 flex items-center justify-center text-5xl font-bold text-gray-300">
                {{ $initials }}
            </div>
        @endif
        
        <div class="flex-1">
            <h1 class="text-4xl font-bold ">{{ $user->username }}</h1>
            <h2 class="text-xl text-gray-300">{{ $user->name }} {{ $user->last_name }}</h2>
            <p class="text-gray-400 mt-2">Se unió el {{ $user->created_at->format('d M, Y') }}</p>

            <div class="flex justify-center md:justify-start gap-4 mt-4">
                <a href="{{ route('user.settings') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-5 rounded-lg transition">
                    Editar Perfil
                </a>
                <a href="{{ route('user.contribute') }}" class="bg-gray-700 hover:bg-gray-600 text-white py-2 px-5 rounded-lg transition">
                    Contribuir
                </a>
            </div>
        </div>
    </div>

    {{-- --- SECCIÓN DE PUBLICACIONES --- --}}
    <h3 class="text-3xl font-bold mb-6 baloo-bhaijaan-2-bold border-b border-gray-700 pb-2">Mis Publicaciones</h3>

    {{-- Mensaje de éxito al crear publicación --}}
    @if (session('success'))
        <div class="bg-green-500/20 border border-green-500/30 text-green-300 text-sm rounded-md p-3 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($publications))
        <div class="text-center text-gray-400 py-16">
            <p class="text-xl">Aún no has hecho ninguna publicación.</p>
            <a href="{{ route('user.contribute') }}" class="mt-4 inline-block text-blue-400 hover:underline text-lg">¡Crea tu primera contribución!</a>
        </div>
    @else
        {{-- Grid "Estilo Instagram" --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
            
            @foreach($publications as $publication)
                {{-- 
                    Este bloque @php ya no es necesario aquí.
                    La lógica de convertir BLOBs e imágenes
                    ya la hace el constructor de PublicationCard.php
                --}}
                @php
                    /*
                    $publicationMedia = $media->get($publication->id, collect());
                    $imagesArray = ...
                    $videosArray = ...
                    */
                @endphp

                {{-- 
                    CORRECCIÓN:
                    Le pasamos las variables que el CONSTRUCTOR espera:
                    - El parámetro $details espera :details
                    - El parámetro $media espera :media
                --}}
                <x-publication-card 
                    :details="$publication" 
                    :media="$media->get($publication->id, collect())"
                    :show-status="true" 
                />
            @endforeach

        </div>
    @endif

</div>
@endsection
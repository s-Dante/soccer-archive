@extends('layouts.app') {{-- Usamos el layout principal --}}

@section('title', 'Mundial ' . $worldCup->year)

 @php
    // --- Lógica de la Vista ---
    
    // 1. Agrupamos las publicaciones por el nombre de la categoría
    $publicationsByCategory = collect($publications)->groupBy('category_name');
    
    // 2. Agrupamos la multimedia por el ID de la publicación para buscarla fácilmente
    $mediaByPublicationId = $media->groupBy('publication_id');

    // 3. Preparamos las imágenes del Infobox (Portada y Balón)
    $coverImage = $worldCup->cover_image 
                  ? 'data:image/jpeg;base64,' . base64_encode($worldCup->cover_image) 
                  : asset('images/logo.png'); // Una imagen por defecto si no hay
    
    $ballImage = $worldCup->ball_image 
                 ? 'data:image/jpeg;base64,' . base64_encode($worldCup->ball_image) 
                 : null;

@endphp 

@section('content')
<div class="container mx-auto px-4 py-12 text-white">
    
    {{-- Encabezado Principal de la Página --}}
    <div class="border-b border-gray-700 pb-4 mb-8">
        <h1 class="text-5xl font-bold baloo-bhaijaan-2-bold">Copa Mundial de la FIFA {{ $worldCup->year }}</h1>
        <h2 class="text-2xl text-gray-300">Sede: {{ $worldCup->host_country }}</h2>
    </div>

    {{-- Contenedor Principal (Estilo Wikipedia: Contenido a la izq, Infobox a la der) --}}
    <div class="flex flex-col-reverse lg:flex-row gap-12">

        {{-- === Columna Izquierda (Contenido Principal) === --}}
        <div class="w-full lg:w-3/4">

            {{-- 1. Descripción Oficial del Admin --}}
            <div class="bg-gray-800/50 p-6 rounded-lg mb-8 border border-gray-700">
                <p class="text-lg text-gray-300 whitespace-pre-wrap">{{ $worldCup->description }}</p>
            </div>

            {{-- 2. Tabla de Contenidos (Índice) --}}
            @if($publicationsByCategory->isNotEmpty())
                <div class="bg-gray-800/50 p-6 rounded-lg mb-10 border border-gray-700 w-full lg:max-w-md">
                    <h3 class="text-2xl font-semibold mb-4 border-b border-gray-600 pb-2">Contenido</h3>
                    <ol class="list-decimal list-inside space-y-2 text-blue-400">
                        @foreach($publicationsByCategory as $categoryName => $pubs)
                            <li>
                                <a href="#seccion-{{ Str::slug($categoryName) }}" class="hover:underline hover:text-blue-300">
                                    {{ $categoryName }} ({{ $pubs->count() }})
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            {{-- 3. Secciones de Publicaciones (agrupadas por categoría) --}}
            <div class="space-y-12">
                @forelse($publicationsByCategory as $categoryName => $pubs)
                    <section id="seccion-{{ Str::slug($categoryName) }}" class="scroll-mt-24">
                        {{-- Título de la Sección/Categoría --}}
                        <h2 class="text-4xl font-bold baloo-bhaijaan-2-bold mb-6 border-b-2 border-blue-500 pb-2">
                            {{ $categoryName }}
                        </h2>
                        
                        {{-- Lista de publicaciones para esta categoría --}}
                        <div class="space-y-8">
                            @foreach($pubs as $publication)
                                <article class="bg-gray-800/50 p-6 rounded-lg border border-gray-700">
                                    <h4 class="text-2xl font-semibold text-white mb-1">{{ $publication->title }}</h4>
                                    <p class="text-sm text-gray-400 mb-4">
                                        Publicado por: {{ $publication->author_name }} 
                                        el {{ \Carbon\Carbon::parse($publication->published_at)->format('d/m/Y') }}
                                    </p>
                                    
                                    {{-- Contenido de Texto --}}
                                    <p class="text-gray-300 whitespace-pre-wrap mb-6">{{ $publication->content }}</p>
                                    
                                    {{-- Contenido Multimedia de esta publicación --}}
                                    @php
                                        $publicationMedia = $mediaByPublicationId->get($publication->id, collect());
                                    @endphp

                                    @if($publicationMedia->isNotEmpty())
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            @foreach($publicationMedia as $item)
                                                @if($item->media_type == 'image' && $item->media_data)
                                                    {{-- Mostrar Imagen --}}
                                                    <a href="data:image/jpeg;base64,{{ base64_encode($item->media_data) }}" target="_blank">
                                                        <img src="data:image/jpeg;base64,{{ base64_encode($item->media_data) }}" 
                                                             alt="Imagen de la publicación" 
                                                             class="rounded-md w-full h-auto object-cover border border-gray-600 hover:opacity-80 transition">
                                                    </a>
                                                @elseif($item->media_type == 'video' && $item->media_url)
                                                    {{-- Mostrar Video (Embed) --}}
                                                    <div class="aspect-w-16 aspect-h-9">
                                                        <iframe src="{{ str_replace("watch?v=", "embed/", $item->media_url) }}" 
                                                                frameborder="0" 
                                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                                allowfullscreen
                                                                class="w-full h-full rounded-md border border-gray-600">
                                                        </iframe>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <x-comments-section 
                                        :publication-id="$publication->id" 
                                        :flat-comments="$commentsByPublication->get($publication->id, [])"
                                    />
                                </article>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="text-center text-gray-400 py-16">
                        <p class="text-xl">Aún no hay contribuciones aprobadas para este mundial.</p>
                        @auth
                            <a href="{{ route('user.contribute') }}" class="mt-4 inline-block text-blue-400 hover:underline text-lg">
                                ¡Sé el primero en contribuir!
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>
        </div>

        {{-- === Columna Derecha (Infobox) === --}}
        <aside class="w-full lg:w-1/4">
            <div class="bg-gray-800/80 backdrop-blur-md rounded-lg shadow-lg border border-gray-700 sticky top-10">
                {{-- Imagen de Portada --}}
                <img src="{{ $coverImage }}" alt="Portada del Mundial {{ $worldCup->year }}" class="rounded-t-lg w-full h-auto">
                
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-4 text-center">{{ $worldCup->year }} - {{ $worldCup->host_country }}</h3>
                    
                    {{-- Imagen del Balón (si existe) --}}
                    @if($ballImage)
                        <div class="text-center mb-4 p-4 bg-gray-900/50 rounded-md">
                            <h4 class="text-sm font-semibold text-gray-300 mb-2">Balón Oficial</h4>
                            <img src="{{ $ballImage }}" alt="Balón del Mundial {{ $worldCup->year }}" class="w-24 h-24 mx-auto object-contain">
                        </div>
                    @endif

                    {{-- (Aquí podríamos añadir más "metadatos" del mundial si los tuviéramos) --}}
                    {{-- <p class="text-sm text-gray-400">Ganador: <span class="font-bold text-white">...</span></p> --}}
                    {{-- <p class="text-sm text-gray-400">Goleador: <span class="font-bold text-white">...</span></p> --}}
                </div>
            </div>
        </aside>

    </div>
</div>
@endsection
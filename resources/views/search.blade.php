@extends('layouts.app') {{-- Usamos el layout principal --}}

@section('title', 'Buscar Publicaciones')

@section('content')
<div class="container mx-auto px-4 py-12 text-white">
    <div class="max-w-6xl mx-auto">
        
        <h1 class="text-4xl font-bold mb-8 baloo-bhaijaan-2-bold">Buscar Contenido</h1>

        {{-- --- 1. FORMULARIO DE FILTROS --- --}}
        <div class="bg-gray-800/80 backdrop-blur-md p-6 sm:p-8 rounded-lg shadow-lg mb-12">
            <form action="{{ route('search.index') }}" method="GET">
                {{-- Usamos method="GET" para que los filtros aparezcan en la URL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    {{-- Filtro 1: Categoría --}}
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-300">Categoría</label>
                        <select id="category_id" name="category_id"
                                class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ (request('category_id') == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro 2: Mundial (Año) --}}
                    <div>
                        <label for="world_cup_id" class="block mb-2 text-sm font-medium text-gray-300">Mundial</label>
                        <select id="world_cup_id" name="world_cup_id"
                                class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los mundiales</option>
                            @foreach($worldCups as $wc)
                                <option value="{{ $wc->id }}"
                                    {{ (request('world_cup_id') == $wc->id) ? 'selected' : '' }}>
                                    {{ $wc->year }} - {{ $wc->host_country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro 3: País Sede --}}
                    <div>
                        <label for="host_country" class="block mb-2 text-sm font-medium text-gray-300">País Sede</label>
                        <input type="text" id="host_country" name="host_country" value="{{ request('host_country') }}"
                               placeholder="Ej: Mexico"
                               class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Filtro 4: Autor (Usuario) --}}
                    <div>
                        <label for="author_name" class="block mb-2 text-sm font-medium text-gray-300">Autor</label>
                        <input type="text" id="author_name" name="author_name" value="{{ request('author_name') }}"
                               placeholder="Nombre o @usuario"
                               class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                {{-- Botones --}}
                <div class="flex items-center gap-4 mt-6">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg transition">
                        Buscar
                    </button>
                    <a href="{{ route('search.index') }}" class="text-sm text-gray-400 hover:underline">
                        Limpiar filtros
                    </a>
                </div>
            </form>
        </div>


        {{-- --- 2. SECCIÓN DE RESULTADOS --- --}}
        <h3 class="text-3xl font-bold mb-6 baloo-bhaijaan-2-bold border-b border-gray-700 pb-2">Resultados</h3>

        @if(empty($publications))
            <div class="text-center text-gray-400 py-16">
                <p class="text-xl">
                    @if(empty(request()->all()))
                        Usa los filtros de arriba para encontrar publicaciones.
                    @else
                        No se encontraron publicaciones que coincidan con tus filtros.
                    @endif
                </p>
            </div>
        @else
            {{-- Grid "Estilo Instagram" (¡Reutilizamos el componente!) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
                
                @foreach($publications as $publication)
                    @php
                        // Preparamos los arrays que el componente espera.
                        $publicationMedia = $media->get($publication->id, collect());
                        
                        $imagesArray = $publicationMedia->where('media_type', 'image')->map(function ($item) {
                            return 'data:image/jpeg;base64,' . base64_encode($item->media_data);
                        })->all();
                        
                        $videosArray = $publicationMedia->where('media_type', 'video')->map(function ($item) {
                            return str_replace("watch?v=", "embed/", $item->media_url);
                        })->all();
                    @endphp

                    <x-publication-card 
                        :publication="$publication" 
                        :images="$imagesArray"
                        :videos="$videosArray"
                        :show-status="false" {{-- No mostramos el estado (ya que solo son 'Aprobados') --}}
                    />
                @endforeach

            </div>
        @endif

    </div>
</div>
@endsection
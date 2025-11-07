{{-- Este es el componente reutilizable de la "Tarjeta de Publicación" --}}
<div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg overflow-hidden max-w-lg w-full">
    
    <!-- Encabezado de la Tarjeta (Datos de la Publicación) -->
    <div class="p-4 border-b border-gray-700">
        <h3 class="text-xl font-bold text-white mb-1">{{ $publication->title }}</h3>
        <div class="flex items-center text-sm text-gray-400 space-x-2">
            <span>Mundial: <span class="font-semibold text-blue-400">{{ $publication->world_cup_year }}</span></span>
            <span>&bull;</span>
            <span>Categoría: <span class="font-semibold text-green-400">{{ $publication->category_name }}</span></span>
        </div>
        <div class="text-sm text-gray-500 mt-1">
            Por: {{ $publication->author_name }}
        </div>
    </div>

    <!-- Contenido Multimedia (Imágenes y Videos) -->
    @if(!empty($images) || !empty($videos))
        <div class="bg-gray-900">
            {{-- Mostramos la primera imagen si existe --}}
            @if(!empty($images))
                <img src="{{ $images[0] }}" alt="Imagen de la publicación" class="w-full h-auto object-cover">
                {{-- (Aquí se podría implementar un carrusel si hay más de 1 imagen) --}}
            @endif

            {{-- Mostramos los videos (si los hay) --}}
            @foreach($videos as $videoUrl)
                <div class="aspect-w-16 aspect-h-9 mt-2">
                    <iframe src="{{ $videoUrl }}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            class="w-full h-full">
                    </iframe>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Descripción y Acciones -->
    <div class="p-4">
        <p class="text-gray-300 whitespace-pre-wrap">{{ $publication->content }}</p>

        {{-- (INICIO) Iconos de Interacción (Añadidos como placeholders para el futuro) --}}
        <div class="flex items-center space-x-6 pt-4 mt-4 border-t border-gray-700">
            <button class="flex items-center space-x-1 text-gray-400 hover:text-white transition-colors">
                <!-- Icono de Like -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <span>Like</span>
            </button>
            <button class="flex items-center space-x-1 text-gray-400 hover:text-white transition-colors">
                <!-- Icono de Comentar -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <span>Comentar</span>
            </button>
        </div>
        {{-- (FIN) Iconos de Interacción --}}
    </div>
</div>
@props(['worldCup'])

<div class="relative flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
    {{-- Enlace a la página de detalle del mundial --}}
    <a href="{{ route('worldcup.show', ['year' => $worldCup->year]) }}" class="block">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            {{-- Manejo de la imagen BLOB --}}
            @if ($worldCup->cover_image)
                <img class="w-full h-48 object-cover" 
                     src="data:image/jpeg;base64,{{ base64_encode($worldCup->cover_image) }}" 
                     alt="Cover de {{ $worldCup->year }}">
            @else
                {{-- Imagen por defecto si no hay una en la BD --}}
                <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                    <span class="text-gray-500">Sin imagen</span>
                </div>
            @endif
            
            <div class="p-4">
                <h3 class="text-white text-xl font-bold">{{ $worldCup->year }}</h3>
                <p class="text-gray-400">{{ $worldCup->host_country }}</p>
            </div>
        </div>
    </a>
</div>
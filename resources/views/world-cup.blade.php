<x-app-layout>
    <div class="container mx-auto px-4 py-8 text-white">

        {{-- Sección de detalles del Mundial --}}
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="md:flex">
                <div class="md:w-1/3">
                    @if ($worldCup->cover_image)
                        <img class="w-full h-full object-cover" src="data:image/jpeg;base64,{{ base64_encode($worldCup->cover_image) }}" alt="Cover de {{ $worldCup->year }}">
                    @else
                        <div class="w-full h-full bg-gray-700"></div>
                    @endif
                </div>
                <div class="p-6 md:w-2/3">
                    <h1 class="text-4xl font-bold mb-2">{{ $worldCup->host_country }} {{ $worldCup->year }}</h1>
                    <p class="text-gray-300 leading-relaxed">{{ $worldCup->description }}</p>
                    
                    @if ($worldCup->ball_image)
                        <div class="mt-4">
                            <h3 class="text-xl font-semibold mb-2">Balón Oficial</h3>
                            <img class="w-24 h-24 object-contain bg-white rounded-full p-2" src="data:image/png;base64,{{ base64_encode($worldCup->ball_image) }}" alt="Balón de {{ $worldCup->year }}">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sección de Publicaciones --}}
        <div>
            <h2 class="text-3xl font-bold mb-6">Publicaciones</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @forelse ($publications as $publication)
                    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $publication->title }}</h3>
                        <p class="text-sm text-gray-400 mb-4">
                            Publicado por: {{ $publication->author_name }} el {{ \Carbon\Carbon::parse($publication->published_at)->format('d/m/Y') }}
                        </p>
                        <p class="text-gray-300">{{ Str::limit($publication->content, 150) }}</p>
                        {{-- Puedes agregar un enlace para ver la publicación completa --}}
                        {{-- <a href="#" class="text-blue-400 hover:underline mt-4 inline-block">Leer más</a> --}}
                    </div>
                @empty
                    <p class="text-gray-400 col-span-full text-center">Aún no hay publicaciones para este mundial.</p>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
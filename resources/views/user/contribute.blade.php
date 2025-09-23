<x-app-layout>
    <div class="container mx-auto px-4 py-8 text-white">
        <h1 class="text-4xl font-bold mb-8">Crear una Nueva Contribución</h1>

        <div class="max-w-2xl mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
            <form action="{{-- route('publications.store') --}}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- Título --}}
                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-300">Título de la Publicación</label>
                    <input type="text" id="title" name="title" placeholder="Ej: El Gol del Siglo de Maradona" required class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                </div>

                {{-- Selección de Mundial --}}
                <div>
                    <label for="world_cup_id" class="block mb-2 text-sm font-medium text-gray-300">Mundial al que Pertenece</label>
                    <select id="world_cup_id" name="world_cup_id" required class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                        <option selected disabled>Elige un mundial...</option>
                        {{-- Aquí poblaremos dinámicamente los mundiales desde la BD --}}
                        {{-- @foreach ($worldCups as $wc) --}}
                        {{--     <option value="{{ $wc->id }}">{{ $wc->year }} - {{ $wc->host_country }}</option> --}}
                        {{-- @endforeach --}}
                        <option value="1">1986 - México</option>
                        <option value="2">2010 - Sudáfrica</option>
                    </select>
                </div>

                {{-- Selección de Categoría --}}
                <div>
                    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-300">Categoría</label>
                    <select id="category_id" name="category_id" required class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                        <option selected disabled>Elige una categoría...</option>
                         {{-- Aquí poblaremos dinámicamente las categorías --}}
                        <option value="1">Goles Históricos</option>
                        <option value="2">Jugadores Leyenda</option>
                        <option value="3">Curiosidades</option>
                    </select>
                </div>

                {{-- Contenido/Descripción --}}
                <div>
                    <label for="content" class="block mb-2 text-sm font-medium text-gray-300">Descripción o Contenido</label>
                    <textarea id="content" name="content" rows="6" placeholder="Describe tu publicación aquí..." required class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5"></textarea>
                </div>

                {{-- Subida de Archivos --}}
                <div>
                    <label for="multimedia" class="block mb-2 text-sm font-medium text-gray-300">Sube tus Archivos (Imágenes o Videos)</label>
                    <input type="file" id="multimedia" name="multimedia[]" multiple class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-lg transition">
                    Enviar para Aprobación
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
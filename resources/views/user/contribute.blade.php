@extends('layouts.app') {{-- Usamos el layout principal --}}

@section('title', 'Crear Contribución')

@section('content')
<div class="container mx-auto px-4 py-12 text-white">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 baloo-bhaijaan-2-bold">Crear una Contribución</h1>

        <div class="bg-gray-800/80 backdrop-blur-md p-6 sm:p-8 rounded-lg shadow-lg">
            <p class="text-gray-300 mb-6">Completa el formulario para enviar tu publicación. Un administrador la revisará antes de que sea visible para todos.</p>
            
            <form action="{{ route('user.contribute.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- --- MOSTRAR ERRORES DE VALIDACIÓN --- --}}
                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 text-sm rounded-md p-4 mb-6">
                        <p class="font-semibold mb-2">Hubo algunos problemas con tu envío:</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- ------------------------------------- --}}

                {{-- Título --}}
                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-300">Título de la Publicación</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Selectores (Mundial y Categoría) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="world_cup_id" class="block mb-2 text-sm font-medium text-gray-300">¿A qué mundial pertenece?</label>
                        <select id="world_cup_id" name="world_cup_id" required
                                class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Selecciona un mundial...</option>
                            @forelse($worldCups as $wc)
                                <option value="{{ $wc->id }}" {{ old('world_cup_id') == $wc->id ? 'selected' : '' }}>
                                    {{ $wc->year }} - {{ $wc->host_country }}
                                </option>
                            @empty
                                <option value="" disabled>No hay mundiales disponibles.</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-300">Categoría</label>
                        <select id="category_id" name="category_id" required
                                class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Selecciona una categoría...</option>
                             @forelse($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @empty
                                <option value="" disabled>No hay categorías disponibles.</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- Contenido/Descripción --}}
                <div>
                    <label for="content" class="block mb-2 text-sm font-medium text-gray-300">Descripción</label>
                    <textarea id="content" name="content" rows="6" required
                              class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Escribe la descripción, historia o datos de tu contribución...">{{ old('content') }}</textarea>
                </div>

                {{-- Sección de Imágenes --}}
                <div class="border-t border-gray-700 pt-6">
                    <label class="block text-sm font-medium text-gray-300">Imágenes (Opcional, Máx. 5)</label>
                    <p class="text-xs text-gray-400 mb-2">Sube hasta 5 imágenes (JPG, PNG, WebP). Máx 2MB cada una.</p>
                    <div id="image-inputs-container" class="space-y-2">
                        {{-- El primer input de imagen --}}
                        <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                    </div>
                    <button type="button" id="add-image-btn" class="mt-2 text-sm text-blue-400 hover:underline">+ Añadir otra imagen</button>
                </div>

                {{-- Sección de Videos --}}
                <div class="border-t border-gray-700 pt-6">
                    <label class="block text-sm font-medium text-gray-300">Videos (Opcional, Máx. 2)</label>
                    <p class="text-xs text-gray-400 mb-2">Pega hasta 2 enlaces de YouTube o Vimeo.</p>
                    <div id="video-inputs-container" class="space-y-2">
                         {{-- El primer input de video --}}
                        <input type="text" name="videos[]" placeholder="https:www.youtube.com/watch?v=..." 
                               class="w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="button" id="add-video-btn" class="mt-2 text-sm text-blue-400 hover:underline">+ Añadir otro video</button>
                </div>

                {{-- Botón de Enviar --}}
                <div class="pt-6">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-3 px-6 rounded-lg transition">
                        Enviar Contribución a Revisión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para añadir campos dinámicamente --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para añadir imágenes
        document.getElementById('add-image-btn').addEventListener('click', function() {
            let container = document.getElementById('image-inputs-container');
            let imageCount = container.querySelectorAll('input[type="file"]').length;
            
            if (imageCount < 5) {
                let newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'images[]';
                newInput.accept = 'image/jpeg,image/png,image/webp';
                newInput.className = 'block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500';
                container.appendChild(newInput);
            } else {
                this.innerText = 'Límite de 5 imágenes alcanzado';
                this.disabled = true;
            }
        });

        // Lógica para añadir videos
        document.getElementById('add-video-btn').addEventListener('click', function() {
            let container = document.getElementById('video-inputs-container');
            let videoCount = container.querySelectorAll('input[type="text"]').length;
            
            if (videoCount < 2) {
                let newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'videos[]';
                newInput.placeholder = 'https:www.youtube.com/watch?v=...';
                newInput.className = 'w-full bg-gray-700 border border-gray-600 rounded p-2.5 text-white focus:ring-blue-500 focus:border-blue-500';
                container.appendChild(newInput);
            } else {
                this.innerText = 'Límite de 2 videos alcanzado';
                this.disabled = true;
            }
        });
    });
</script>
@endsection
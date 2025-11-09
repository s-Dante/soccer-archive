{{-- 4. SUB-COMPONENTE (VISTA) PARA UN ITEM DE COMENTARIO --}}
{{-- Como Blade no soporta bucles `@include` recursivos fácilmente, creamos un archivo separado. --}}
{{-- Instrucción: Crea el archivo 'resources/views/components/comment-item.blade.php' y pega el código de abajo --}}
@if(false) 
    Este código no se ejecuta, es solo una nota. 
    El código de abajo debes ponerlo en un archivo NUEVO llamado:
    resources/views/components/comment-item.blade.php
@endif


{{-- CÓDIGO PARA 'resources/views/components/comment-item.blade.php' --}}
<div class="flex items-start space-x-3" data-comment-id="{{ $comment->id }}">
    {{-- Avatar --}}
    <img class="h-10 w-10 rounded-full object-cover" 
         src="{{ $avatar }}" 
         alt="{{ $comment->author_name }}'s profile photo">
    
    <div class="flex-1">
        {{-- Contenido del Comentario --}}
        <div class="bg-gray-800 p-3 rounded-lg">
            <p class="text-sm font-semibold text-white mb-1">{{ $comment->author_name }}</p>
            <p class="text-white whitespace-pre-wrap">{{ $comment->content }}</p>
        </div>
        
        {{-- Acciones (Timestamp y Botón de Responder) --}}
        <div class="flex items-center space-x-3 text-xs text-gray-500 mt-1">
            <span>{{ \Carbon\Carbon::parse($comment->commented_at)->diffForHumans() }}</span>
            @auth
                <button class="font-semibold hover:underline" data-reply-btn>Responder</button>
            @endauth
        </div>

        {{-- Formulario de Respuesta (oculto por defecto) --}}
        @auth
            <form class="hidden mt-3 ml-8" data-comment-form data-parent-id="{{ $comment->id }}">
                <textarea name="content" class="w-full bg-gray-700 border border-gray-600 rounded-md p-2 text-sm text-white" rows="2" placeholder="Escribe una respuesta..." required></textarea>
                <div class="flex gap-2 mt-1">
                    <button type="submit" class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-md transition">Enviar</button>
                    <button type="button" class="text-xs px-3 py-1 bg-gray-600 hover:bg-gray-500 text-white font-semibold rounded-md transition" data-cancel-reply-btn>Cancelar</button>
                </div>
            </form>
        @endauth
        
        {{-- 5. BUCLE DE RESPUESTAS (aquí está tu lógica de 1 nivel) --}}
        <div class="space-y-4 mt-4 ml-8" data-replies-container>
            @foreach($comment->replies as $reply)
                <div class="flex items-start space-x-3" data-comment-id="{{ $reply->id }}">
                    <img class="h-8 w-8 rounded-full object-cover" 
                         src="{{ $reply->author_photo ? 'data:image/jpeg;base64,'.base64_encode($reply->author_photo) : 'https://ui-avatars.com/api/?name='.urlencode($reply->author_name).'&background=4B5563&color=ffffff&rounded=true' }}" 
                         alt="{{ $reply->author_name }}'s profile photo">
                    <div class="flex-1">
                        <div class="bg-gray-700/50 p-3 rounded-lg">
                            <p class="text-sm font-semibold text-white mb-1">{{ $reply->author_name }}</p>
                            <p class="text-white whitespace-pre-wrap">{{ $reply->content }}</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span>{{ \Carbon\Carbon::parse($reply->commented_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
{{-- FIN DEL CÓDIGO PARA 'resources/views/components/comment-item.blade.php' --}}
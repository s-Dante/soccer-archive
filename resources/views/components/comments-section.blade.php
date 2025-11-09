{{-- 
Componente: resources/views/components/comments-section.blade.php
Recibe: $publicationId (int) y $comments (Collection jerarquizada)
--}}
<div class="mt-12 pt-8 border-t border-gray-700" id="comments-section">
    <h3 class="text-3xl font-semibold mb-6">Comentarios ({{ $comments->count() }})</h3>

    {{-- 1. FORMULARIO PARA NUEVO COMENTARIO (PADRE) --}}
    @auth
        <form data-comment-form data-parent-id="">
            <div class="flex items-start space-x-3 mb-6">
                <img class="h-10 w-10 rounded-full object-cover" 
                     src="{{ Auth::user()->profile_photo ? 'data:image/jpeg;base64,'.base64_encode(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4B5563&color=ffffff&rounded=true' }}" 
                     alt="Tu foto de perfil">
                <div class="flex-1">
                    <textarea name="content" 
                              class="w-full bg-gray-800 border border-gray-700 rounded-md p-3 text-white placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" 
                              rows="3" 
                              placeholder="Escribe un comentario..." 
                              required></textarea>
                    <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-md transition">
                        Comentar
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="bg-gray-800 p-4 rounded-md text-center text-gray-400 mb-6">
            <a href="{{ route('auth.login') }}" class="font-semibold text-blue-400 hover:underline">Inicia sesión</a> para dejar un comentario.
        </div>
    @endauth

    {{-- 2. LISTA DE COMENTARIOS --}}
    <div class="space-y-6" id="comments-list">
        
        @if($comments->isEmpty())
            <p class="text-gray-500">Aún no hay comentarios. ¡Sé el primero!</p>
        @endif

        @foreach($comments as $comment)
            {{-- Pasamos el comentario y la lógica del avatar al sub-componente --}}
            @include('components.comment-item', ['comment' => $comment, 'avatar' => $this->getAvatar($comment)])
        @endforeach
    </div>

</div>


{{-- 3. PLANTILLA PARA NUEVOS COMENTARIOS (para AJAX) --}}
{{-- Esta plantilla estará oculta y la usaremos con JS para añadir comentarios en tiempo real --}}
<template id="comment-template">
    <div class="flex items-start space-x-3" data-comment-id="">
        <img class="h-10 w-10 rounded-full object-cover" data-avatar-src="" alt="Foto de perfil">
        <div class="flex-1">
            <div class="bg-gray-800 p-3 rounded-lg">
                <p class="text-sm font-semibold text-white mb-1" data-author-name=""></p>
                <p class="text-white whitespace-pre-wrap" data-content=""></p>
            </div>
            <div classs="flex items-center space-x-3 text-xs text-gray-500 mt-1">
                <span data-timestamp=""></span>
                <button class="font-semibold hover:underline" data-reply-btn>Responder</button>
            </div>
            
            {{-- Formulario de respuesta (inicialmente oculto) --}}
            <form class="hidden mt-3 ml-8" data-comment-form data-parent-id="">
                <textarea name="content" class="w-full bg-gray-700 border border-gray-600 rounded-md p-2 text-sm text-white" rows="2" placeholder="Escribe una respuesta..." required></textarea>
                <div class="flex gap-2 mt-1">
                    <button type="submit" class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-md transition">Enviar</button>
                    <button type="button" class="text-xs px-3 py-1 bg-gray-600 hover:bg-gray-500 text-white font-semibold rounded-md transition" data-cancel-reply-btn>Cancelar</button>
                </div>
            </form>
            
            {{-- Contenedor para las respuestas (inicialmente vacío) --}}
            <div class="space-y-4 mt-4 ml-8" data-replies-container></div>
        </div>
    </div>
</template>


{{-- 6. TODO EL JAVASCRIPT (AJAX) PARA LA SECCIÓN DE COMENTARIOS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const commentsSection = document.getElementById('comments-section');
    if (!commentsSection) return;

    const commentsList = commentsSection.querySelector('#comments-list');
    const template = document.getElementById('comment-template');

    // Manejo seguro del CSRF + publicationId + datos del usuario autenticado (si están)
    const meta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = meta ? meta.getAttribute('content') : null;
    const publicationId = {{ $publicationId }}; // viene del componente
    // userName/userAvatar: los inyectamos de forma condicional por Blade (evita errores si el visitante es invitado)
    @auth
        const userAvatar = "{{ Auth::user()->profile_photo ? 'data:image/jpeg;base64,'.base64_encode(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4B5563&color=ffffff&rounded=true' }}";
        const userName = "{{ addslashes(Auth::user()->name) }}";
    @else
        const userAvatar = null;
        const userName = "Usuario";
    @endauth

    // Envío de formularios (tanto principal como respuestas)
    const handleFormSubmit = async (form) => {
        const textarea = form.querySelector('textarea[name="content"]');
        if (!textarea) return;
        const content = textarea.value.trim();
        const parentId = form.dataset.parentId || null;

        if (!content) {
            alert('Escribe un comentario antes de enviar.');
            return;
        }

        // Deshabilitar botón para evitar doble envío
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        try {
            const response = await fetch(`/publications/${publicationId}/comments`, {
                method: 'POST',
                credentials: 'same-origin', // importante para sesión/CSRF
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    content: content,
                    parent_id: parentId
                })
            });

            if (!response.ok) {
                // leer y mostrar mensaje si viene JSON
                let msg = `Error ${response.status}`;
                try {
                    const jsonErr = await response.json();
                    msg = jsonErr.message || JSON.stringify(jsonErr);
                } catch(e){}
                throw new Error(msg);
            }

            const newComment = await response.json();

            // limpiar campo
            textarea.value = '';
            if (parentId) {
                form.classList.add('hidden');
            }

            // agregar al DOM
            addCommentToDOM(newComment);

        } catch (error) {
            console.error('Error al enviar comentario:', error);
            alert('No se pudo enviar tu comentario: ' + (error.message || 'Error desconocido'));
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    };

    // Añadir comentario al DOM a partir de la plantilla
    // Añadir comentario al DOM a partir de la plantilla
    const addCommentToDOM = (comment) => {
        if (!template) return;

        // clonamos la plantilla (DocumentFragment)
        const fragment = template.content.cloneNode(true);

        // El primer elemento hijo del fragment es el <div data-comment-id="">
        const newNode = fragment.firstElementChild;
        if (!newNode) return;

        const parentId = comment.parent_id ?? null;

        // Rellena datos en el nuevo nodo
        newNode.dataset.commentId = comment.id;

        // Avatar
        const avatarEl = newNode.querySelector('[data-avatar-src]');
        if (comment.author_photo) {
            // author_photo viene ya como base64 (sin prefijo)
            avatarEl.src = 'data:image/jpeg;base64,' + comment.author_photo;
        } else {
            avatarEl.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(comment.author_name)}&background=4B5563&color=ffffff&rounded=true`;
        }

        // Texto y meta
        const authorNameEl = newNode.querySelector('[data-author-name]');
        const contentEl = newNode.querySelector('[data-content]');
        const timestampEl = newNode.querySelector('[data-timestamp]');

        if (authorNameEl) authorNameEl.textContent = comment.author_name;
        if (contentEl) contentEl.textContent = comment.content;
        if (timestampEl) timestampEl.textContent = comment.commented_at ? comment.commented_at : 'Justo ahora';

        // Configuramos el formulario de respuesta (si existe)
        const replyForm = newNode.querySelector('[data-comment-form]');
        if (replyForm) replyForm.dataset.parentId = comment.id;

        // Insertarlo en el DOM: respuesta o comentario padre
        if (parentId) {
            const parentContainer = commentsList.querySelector(`[data-comment-id="${parentId}"] [data-replies-container]`);
            if (parentContainer) {
                parentContainer.appendChild(newNode);
            } else {
                // fallback: agregar al final
                commentsList.appendChild(newNode);
            }
        } else {
            commentsList.appendChild(newNode);
        }

        // --- ACTUALIZAR EL CONTADOR EN EL ENCABEZADO ---
        // Busca el <h3> del encabezado dentro del componente
        const headerH3 = commentsSection.querySelector('h3');
        if (headerH3) {
            // Extraer número actual si existe y actualizar +1
            const match = headerH3.textContent.match(/\((\d+)\)$/);
            let current = match ? parseInt(match[1], 10) : (Array.from(commentsList.children).length);
            // Si agregamos un comentario padre, incrementamos en 1.
            // Si añadimos respuesta, podríamos querer mantener el mismo conteo de "comentarios totales"
            // Aquí incrementamos siempre 1 (total de comentarios incluyendo respuestas)
            current = current + 1;
            // Reemplazar texto: "Comentarios (N)"
            headerH3.textContent = `Comentarios (${current})`;
        }
    };


    // Mostrar/ocultar formulario reply
    const toggleReplyForm = (button) => {
        const commentElement = button.closest('[data-comment-id]');
        const form = commentElement.querySelector('[data-comment-form]');
        if (!form) return;
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.querySelector('textarea').focus();
        }
    };

    // Delegación de eventos
    commentsSection.addEventListener('click', (e) => {
        if (e.target.matches('[data-reply-btn]')) {
            toggleReplyForm(e.target);
        }
        if (e.target.matches('[data-cancel-reply-btn]')) {
            const f = e.target.closest('form');
            if (f) f.classList.add('hidden');
        }
    });

    // Delegación para submit de formularios de comentarios
    commentsSection.addEventListener('submit', (e) => {
        if (e.target.matches('[data-comment-form]')) {
            e.preventDefault();
            handleFormSubmit(e.target);
        }
    });

});
</script>

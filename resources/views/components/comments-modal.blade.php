{{-- 
    NUEVO ARCHIVO: resources/views/components/comments-modal.blade.php
    Este componente contendrá el modal y TODO el JavaScript para 
    mostrarlo, obtener datos y enviar nuevos comentarios.
--}}

{{-- Fondo oscuro del modal --}}
<div id="comments-modal" 
     class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-40 flex items-center justify-center p-4 hidden"
     data-modal-overlay>
    
    {{-- Contenedor del Modal --}}
    <div class="bg-gray-800 border border-gray-700 text-white rounded-lg shadow-xl w-full max-w-4xl h-[90vh] flex flex-col overflow-hidden">
        
        {{-- Encabezado del Modal (con botón de cerrar) --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <h3 class="text-xl font-semibold">Comentarios</h3>
            <button data-modal-close class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Contenido del Modal --}}
        <div class="flex-1 overflow-y-auto">
            
            {{-- 1. Loader (se muestra mientras cargan los datos) --}}
            <div data-modal-loader class="p-12 flex items-center justify-center">
                <span class="text-gray-400 text-lg">Cargando...</span>
            </div>

            {{-- 2. Contenido (inicialmente oculto) --}}
            <div data-modal-content class="hidden">
                
                {{-- 2.1. Descripción/Caption de la Publicación --}}
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <img id="modal-author-avatar" class="h-10 w-10 rounded-full object-cover" src="" alt="Avatar">
                        <div class="flex-1">
                            <span id="modal-author-name" class="text-sm font-semibold text-white"></span>
                        </div>
                    </div>
                    <h4 id="modal-publication-title" class="text-white font-semibold mt-3"></h4>
                    <p id="modal-publication-content" class="text-gray-300 whitespace-pre-wrap mt-1"></p>
                </div>

                {{-- 2.2. Lista de Comentarios (se llenará con JS) --}}
                <div id="modal-comments-list" class="space-y-6 p-4">
                    {{-- Los comentarios se insertarán aquí --}}
                </div>
            </div>
        </div>

        {{-- 3. Formulario para nuevo comentario (fijo al final) --}}
        <div class="p-4 border-t border-gray-700 bg-gray-800">
            @auth
                {{-- 
                    Usamos el mismo formulario de antes, pero ahora el JS 
                    lo manejará en el contexto del modal.
                --}}
                <form data-comment-form data-parent-id="">
                    <div class="flex items-start space-x-3">
                        <img class="h-10 w-10 rounded-full object-cover" 
                             src="{{ Auth::user()->profile_photo ? 'data:image/jpeg;base64,'.base64_encode(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4B5563&color=ffffff&rounded=true' }}" 
                             alt="Tu foto de perfil">
                        <div class="flex-1">
                            <textarea name="content" 
                                      class="w-full bg-gray-700 border border-gray-600 rounded-md p-3 text-sm text-white placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" 
                                      rows="2" 
                                      placeholder="Escribe un comentario..." 
                                      required></textarea>
                            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-md transition text-sm">
                                Publicar
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center text-gray-400">
                    <a href="{{ route('auth.login') }}" class="font-semibold text-blue-400 hover:underline">Inicia sesión</a> para comentar.
                </div>
            @endauth
        </div>

    </div>
</div>


{{-- 
    PLANTILLA DE COMENTARIOS
    Copiada exactemente de tu 'comments-section.blade.php'.
    La usaremos para renderizar comentarios con JS.
--}}
<template id="comment-template">
    <div class="flex items-start space-x-3" data-comment-id="">
        <img class="h-10 w-10 rounded-full object-cover" data-avatar-src="" alt="Foto de perfil">
        <div class="flex-1">
            <div class="bg-gray-700 p-3 rounded-lg">
                <p class="text-sm font-semibold text-white mb-1" data-author-name=""></p>
                <p class="text-white whitespace-pre-wrap text-sm" data-content=""></p>
            </div>
            <div classs="flex items-center space-x-3 text-xs text-gray-500 mt-1">
                <span data-timestamp=""></span>
                {{-- 
                    NOTA: La funcionalidad de "Responder" (anidar) es compleja.
                    La plantilla la soporta, y el JS de abajo la construirá.
                --}}
                <button class="font-semibold hover:underline" data-reply-btn>Responder</button>
            </div>
            
            {{-- Formulario de respuesta (inicialmente oculto) --}}
            <form class="hidden mt-3 ml-8" data-comment-form data-parent-id="">
                <textarea name="content" class="w-full bg-gray-600 border border-gray-500 rounded-md p-2 text-sm text-white" rows="2" placeholder="Escribe una respuesta..." required></textarea>
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


{{-- 
    TODO EL JAVASCRIPT DEL MODAL
    Este script maneja ABRIR, CERRAR, CARGAR DATOS, y ENVIAR COMENTARIOS.
--}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- 1. Referencias a elementos del Modal ---
    const modal = document.getElementById('comments-modal');
    if (!modal) return;

    const modalCloseBtn = modal.querySelector('[data-modal-close]');
    const modalLoader = modal.querySelector('[data-modal-loader]');
    const modalContent = modal.querySelector('[data-modal-content]');
    const commentsList = modal.querySelector('#modal-comments-list');
    const commentTemplate = document.getElementById('comment-template');

    const modalAuthorAvatar = modal.querySelector('#modal-author-avatar');
    const modalAuthorName = modal.querySelector('#modal-author-name');
    const modalPublicationTitle = modal.querySelector('#modal-publication-title');
    const modalPublicationContent = modal.querySelector('#modal-publication-content');

    const mainCommentForm = modal.querySelector('form[data-comment-form]');

    // --- Estado global útil ---
    let currentPublicationId = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Map de comments por id para lógica (permite normalizar replies)
    let commentsById = {};

    // --- Función: ABRIR modal y cargar datos ---
    const openModal = async (publicationId) => {
        currentPublicationId = publicationId;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        modalLoader.classList.remove('hidden');
        modalContent.classList.add('hidden');
        commentsList.innerHTML = '';
        commentsById = {}; // resetear

        try {
            const response = await fetch(`/publications/${publicationId}`, {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error(`Error ${response.status}`);

            const data = await response.json();
            const pub = data.publication;

            modalAuthorAvatar.src = pub.author_avatar || '';
            modalAuthorName.textContent = pub.author_name || '';
            modalPublicationTitle.textContent = pub.title || '';
            modalPublicationContent.textContent = pub.content || '';

            // Construir mapa y listas
            const rootComments = [];
            const replyBuffer = [];

            (data.comments || []).forEach(c => {
                commentsById[c.id] = c;
                // ensure parent_id is null when empty string / 0
                c.parent_id = c.parent_id ? c.parent_id : null;
                if (c.parent_id) replyBuffer.push(c);
                else rootComments.push(c);
            });

            // Render: primero padres, luego replies (addCommentToDOM maneja append correcto)
            rootComments.forEach(c => addCommentToDOM(c));
            replyBuffer.forEach(r => addCommentToDOM(r));

            modalLoader.classList.add('hidden');
            modalContent.classList.remove('hidden');

        } catch (error) {
            console.error('Error al cargar la publicación:', error);
            modalLoader.innerHTML = `<span class="text-red-400">Error al cargar comentarios. (${error.message || ''})</span>`;
        }
    };

    // --- CERRAR modal ---
    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentPublicationId = null;
        modalLoader.innerHTML = '<span class="text-gray-400 text-lg">Cargando...</span>';
        commentsById = {};
    };

    // --- Event listeners para abrir/cerrar ---
    document.body.addEventListener('click', (e) => {
        const commentButton = e.target.closest('[data-comment-button]');
        if (commentButton) {
            e.preventDefault();
            const pubId = commentButton.dataset.publicationId;
            if (pubId) openModal(pubId);
        }
    });

    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    // --- Añade un comentario al DOM (limita anidación a 1 nivel) ---
    const addCommentToDOM = (comment) => {
        if (!commentTemplate) return;

        const fragment = commentTemplate.content.cloneNode(true);
        const newNode = fragment.firstElementChild;
        if (!newNode) return;

        // normalizar parentId
        let parentId = comment.parent_id ?? null;

        newNode.dataset.commentId = comment.id;

        // Avatar
        const avatarEl = newNode.querySelector('[data-avatar-src]');
        if (avatarEl) {
            // si el backend envía author_avatar (data:...), úsalo directamente
            avatarEl.src = comment.author_avatar || (comment.author_avatar ? comment.author_avatar : (`https://ui-avatars.com/api/?name=${encodeURIComponent(comment.author_name || 'Usuario')}&background=4B5563&color=ffffff&rounded=true`));
        }

        // Contenido
        const authorNameEl = newNode.querySelector('[data-author-name]');
        if (authorNameEl) authorNameEl.textContent = comment.author_name || 'Usuario';
        const contentEl = newNode.querySelector('[data-content]');
        if (contentEl) contentEl.textContent = comment.content || '';
        const timestampEl = newNode.querySelector('[data-timestamp]');
        if (timestampEl) timestampEl.textContent = comment.commented_at || 'Justo ahora';

        // Si el comentario ES una respuesta (parent_id != null) -> quitar la opción de responder
        if (parentId) {
            const replyBtn = newNode.querySelector('[data-reply-btn]');
            if (replyBtn) replyBtn.remove(); // no permitimos 'responder' a una respuesta
            const innerReplyForm = newNode.querySelector('[data-comment-form]');
            if (innerReplyForm) innerReplyForm.remove(); // quitar el pequeño form dentro de la tarjeta
        }

        // Aseguramos que el reply form (si existe) tenga dataset parentId correcto
        const replyForm = newNode.querySelector('[data-comment-form]');
        if (replyForm) replyForm.dataset.parentId = comment.id;

        // Insertar: si parentId existe, anexarlo como respuesta del padre; si no, al final
        let container = commentsList;
        if (parentId) {
            // Si el padre existe en DOM, insertar ahí
            const parentNode = commentsList.querySelector(`[data-comment-id="${parentId}"] [data-replies-container]`);
            if (parentNode) {
                container = parentNode;
            } else {
                // Si no encontramos el padre (edge-case), lo anexamos al final
                container = commentsList;
            }
        }
        container.appendChild(newNode);
    };

    // --- Enviar nuevo comentario / reply (normaliza parent_id para no superar 1 nivel) ---
    const handleFormSubmit = async (form) => {
        const textarea = form.querySelector('textarea[name="content"]');
        const rawParentId = form.dataset.parentId || null;
        let parentId = rawParentId ? rawParentId : null;
        const content = textarea?.value?.trim?.() ?? '';

        if (!content || !currentPublicationId) return;

        // Si el parentId es una respuesta (es decir, tiene parent_id != null), normalizamos
        if (parentId && commentsById[parentId] && commentsById[parentId].parent_id) {
            // asociamos al root del hilo (no permitimos más de 1 nivel)
            parentId = commentsById[parentId].parent_id;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        try {
            const response = await fetch(`/publications/${currentPublicationId}/comments`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: content, parent_id: parentId })
            });

            if (!response.ok) {
                let msg = `Error ${response.status}`;
                try { const jsonErr = await response.json(); msg = jsonErr.message || JSON.stringify(jsonErr); } catch(e){}
                throw new Error(msg);
            }

            const result = await response.json();

            if (result.success && result.comment) {
                // Construimos objecto normalizado para el nuevo comentario
                const created = result.comment;
                const newCommentData = {
                    id: created.id,
                    content: created.content,
                    parent_id: created.parent_id ? created.parent_id : null,
                    author_name: created.author_name || (created.user_name ?? 'Tú'),
                    // si tu API devuelve author_avatar/author_photo ajusta aquí
                    author_avatar: created.author_avatar || (created.author_photo ? ('data:image/jpeg;base64,' + created.author_photo) : null),
                    commented_at: 'Justo ahora'
                };

                // Guardar en mapa para futuras normalizaciones
                commentsById[newCommentData.id] = newCommentData;

                // Si fue reply, opcional: anclar al parent real (ya normalizamos parentId)
                addCommentToDOM(newCommentData);

                // limpiar UI
                if (textarea) textarea.value = '';
                if (parentId && form) form.classList.add('hidden');

            } else {
                throw new Error('Respuesta de API no válida al crear comentario');
            }

        } catch (error) {
            console.error('Error al enviar comentario:', error);
            alert('No se pudo enviar tu comentario: ' + (error.message || 'Error desconocido'));
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    };

    // Delegación dentro del modal: toggles y submits
    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-reply-btn]')) {
            const commentElement = e.target.closest('[data-comment-id]');
            const form = commentElement.querySelector('[data-comment-form]');
            if (form) {
                form.classList.toggle('hidden');
                if (!form.classList.contains('hidden')) form.querySelector('textarea').focus();
            }
        }
        if (e.target.matches('[data-cancel-reply-btn]')) {
            const f = e.target.closest('form');
            if (f) f.classList.add('hidden');
        }
    });

    modal.addEventListener('submit', (e) => {
        if (e.target.matches('[data-comment-form]')) {
            e.preventDefault();
            handleFormSubmit(e.target);
        }
    });

});
</script>

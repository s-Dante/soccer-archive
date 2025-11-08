@php
    use Illuminate\Support\Str;

    // Unificamos imágenes y videos
    $media = [];
    foreach (($images ?? []) as $src) $media[] = ['type' => 'image', 'src' => $src];
    foreach (($videos ?? []) as $src) $media[] = ['type' => 'video', 'src' => $src];

    // ID único para enlazar el JS de este post
    $uid = 'post-'.($publication->id ?? Str::uuid());
@endphp

<div data-post="{{ $uid }}" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden max-w-md w-full">

    @if($showStatus ?? false)
        <div class="left-3 top-3 z-20">
            @if($publication->status === 'accepted')
                <span class="text-xs font-semibold inline-block py-1 px-3 uppercase rounded-full text-green-200 bg-green-700/70 shadow-sm">
                    Aprobado
                </span>
            @elseif($publication->status === 'rejected')
                <span class="text-xs font-semibold inline-block py-1 px-3 uppercase rounded-full text-orange-200 bg-orange-700/70 shadow-sm">
                    Rechazado
                </span>
            @else
                <span class="text-xs font-semibold inline-block py-1 px-3 uppercase rounded-full text-yellow-100 bg-yellow-700/60 shadow-sm">
                    Pendiente
                </span>
            @endif
        </div>
    @endif

    {{-- Header (sin menú de 3 puntos) --}}
    <div class="flex items-center px-4 py-3">
        <img src="{{ $publication->author_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($publication->author_name) . '&background=111827&color=fff' }}"
             alt="avatar" class="w-9 h-9 rounded-full object-cover mr-3">
        <div class="leading-tight">
            <div class="text-sm font-semibold text-white">{{ $publication->author_name }}</div>
            <div class="text-xs text-gray-400">
                Mundial {{ $publication->world_cup_year }} • {{ $publication->category_name }}
            </div>
        </div>
    </div>


    {{-- Carrusel (solo si hay media) --}}
    @if(count($media))
        <div class="relative group bg-black select-none">

            {{-- Pista scrollable con snap --}}
            <div data-track
                class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar w-full"
                style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($media as $m)
                <div class="w-full flex-none snap-start bg-black">
                    {{-- Contenedor cuadrado unificado y centrado --}}
                    <div class="relative w-full aspect-square flex items-center justify-center bg-black">

                    @if($m['type'] === 'image')
                        {{-- Imagen centrada/letterbox --}}
                        <img src="{{ $m['src'] }}" alt="imagen"
                            class="max-w-full max-h-full object-contain" />

                    @else
                        @php
                        $isIframe = str_contains($m['src'], 'youtube.com')
                                || str_contains($m['src'], 'youtu.be')
                                || str_contains($m['src'], 'vimeo.com');
                        @endphp

                        @if($isIframe)
                        {{-- Iframe centrado dentro del cuadrado (letterbox) --}}
                        <div class="flex items-center justify-center w-full h-full">
                            <div class="relative aspect-video w-[92%] max-h-[92%]">
                            <iframe src="{{ $m['src'] }}"
                                    class="absolute inset-0 w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                        @else
                        {{-- <video> centrado dentro del cuadrado (letterbox) --}}
                        <video src="{{ $m['src'] }}"
                                class="max-w-[92%] max-h-[92%] object-contain bg-black"
                                controls playsinline preload="metadata"></video>
                        @endif

                    @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Flechas / Dots (sin cambios) --}}
            <button data-prev
                    class="hidden sm:flex absolute left-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7"/>
                </svg>
            </button>
            <button data-next
                    class="hidden sm:flex absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-md opacity-0 group-hover:opacity-100 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                </svg>
            </button>

            <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5">
                @for($i=0; $i<count($media); $i++)
                <button data-dot="{{ $i }}"
                        class="h-1.5 rounded-full transition w-1.5 bg-white/50"></button>
                @endfor
            </div>
        </div>
    @endif


    {{-- Acciones: Tarjeta Verde (izquierda) y Comentar (derecha) --}}
    <div class="px-4 pt-3">
        <div class="flex items-center justify-between">

            {{-- ================================================ --}}
            {{-- ==== INICIO DEL CÓDIGO DE "TARJETA VERDE" ==== --}}
            {{-- ================================================ --}}

            {{-- 
                Este botón ahora es dinámico:
                1. data-id: Pasa el ID de la publicación al JS.
                2. data-like-button: Es el selector para el JS.
                3. class: Cambia de color si 'has_liked' (del SP) es 1.
                4. fill: Rellena el SVG si 'has_liked' es 1.
            --}}
            <button 
                data-like-button {{-- Identificador para el JS --}}
                data-id="{{ $publication->id }}" {{-- El ID de la publicación --}}
                @guest disabled @endguest {{-- Deshabilitar si el usuario no ha iniciado sesión --}}
                class="flex items-center gap-1 transition-colors disabled:opacity-50
                    {{-- Si 'has_liked' (del SP) es 1, píntalo de verde; si no, gris --}}
                    @if($publication->has_liked)
                        text-green-500 hover:text-green-400 {{-- Estado "Activado" --}}
                    @else
                        text-gray-400 hover:text-white {{-- Estado "Desactivado" --}}
                    @endif
                "
            >
                {{-- Icono de Tarjeta (Corazón) --}}
                <svg class="w-7 h-7" viewBox="0 0 24 24" 
                    fill="{{ $publication->has_liked ? 'currentColor' : 'none' }}" {{-- Relleno dinámico --}}
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                
                {{-- Texto temático --}}
                <span class="text-sm font-medium">Tarjeta Verde</span>

                {{-- Conteo de Likes (con un selector para el JS) --}}
                <span data-like-count class="text-sm font-medium">
                    {{ $publication->like_count }}
                </span>
            </button>
            {{-- ================================================ --}}
            {{-- ====== FIN DEL CÓDIGO DE "TARJETA VERDE" ====== --}}
            {{-- ================================================ --}}


            <button class="text-gray-400 hover:text-white flex items-center gap-1">
                {{-- Comment --}}
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="text-sm">Comentar</span>
            </button>
        </div>
    </div>

    {{-- Caption / Solo texto también funciona (si no hay media, esto es lo único que se ve) --}}
    <div class="px-4 py-3">
        <h3 class="text-white font-semibold">{{ $publication->title }}</h3>
        <p class="text-gray-200">
            <span class="font-semibold">{{ $publication->author_name }}</span>
            <span class="whitespace-pre-wrap"> {{ $publication->content }}</span>
        </p>
        <div class="text-xs text-gray-500 mt-2">
            {{ \Carbon\Carbon::parse($publication->created_at ?? now())->diffForHumans() }}
        </div>
    </div>
</div>



{{-- JS mínimo para que funcionen los botones y los dots (sin Alpine) --}}
<script>
// Usamos una función anónima para evitar colisiones de variables
(function() {
    // Esperamos a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupCard);
    } else {
        setupCard();
    }

    function setupCard() {
        const root = document.querySelector('[data-post="{{ $uid }}"]');
        if (!root || root.dataset.initialized) {
            return; // Si no existe o ya se inicializó, no hacemos nada
        }
        root.dataset.initialized = true; // Marcamos como inicializado

        // --- LÓGICA DEL CARRUSEL (Tu código original) ---
        const track = root.querySelector('[data-track]');
        const slides = track ? Array.from(track.children) : [];
        const prevBtn = root.querySelector('[data-prev]');
        const nextBtn = root.querySelector('[data-next]');
        const dots = Array.from(root.querySelectorAll('[data-dot]'));
        let i = 0;

        const snapTo = (idx) => {
            if (!track) return;
            i = Math.max(0, Math.min(idx, slides.length - 1));
            track.scrollTo({ left: track.clientWidth * i, behavior: 'smooth' });
            updateDots();
            updateArrows();
        };

        const updateDots = () => {
            dots.forEach((d, idx) => {
                d.classList.toggle('w-6', idx === i);
                d.classList.toggle('bg-white', idx === i);
                d.classList.toggle('w-1.5', idx !== i);
                d.classList.toggle('bg-white/50', idx !== i);
            });
        };

        const updateArrows = () => {
            if (!prevBtn || !nextBtn) return;
            prevBtn.classList.toggle('opacity-30', i === 0);
            nextBtn.classList.toggle('opacity-30', i === slides.length - 1);
            prevBtn.classList.toggle('pointer-events-none', i === 0);
            nextBtn.classList.toggle('pointer-events-none', i === slides.length - 1);
        };

        if (track) {
            if (prevBtn) prevBtn.addEventListener('click', () => snapTo(i - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => snapTo(i + 1));
            dots.forEach((d, idx) => d.addEventListener('click', () => snapTo(idx)));

            track.addEventListener('scroll', () => {
                const idx = Math.round(track.scrollLeft / track.clientWidth);
                if (idx !== i) { i = idx; updateDots(); updateArrows(); }
            });
            updateDots();
            updateArrows();
        }

        // --- LÓGICA DE TARJETA VERDE (LIKE) ---
        const likeButton = root.querySelector('[data-like-button]');
        const likeCountSpan = root.querySelector('[data-like-count]');
        const likeIcon = likeButton ? likeButton.querySelector('svg') : null;

        if (likeButton && likeCountSpan && likeIcon) {
            
            likeButton.addEventListener('click', async () => {
                // Prevenimos que el usuario de spam de clics
                if (likeButton.disabled) return; 
                likeButton.disabled = true;

                const publicationId = likeButton.dataset.id;
                
                // 1. Obtener el token CSRF (¡ESENCIAL!)
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    // 2. Llamar a la API que creamos
                    const response = await fetch(`/api/publications/${publicationId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }

                    const result = await response.json();

                    // 3. Actualizar el botón y el conteo en tiempo real
                    if (result.success) {
                        const hasLiked = result.status === 'liked';
                        
                        // Actualizar color del botón
                        likeButton.classList.toggle('text-green-500', hasLiked);
                        likeButton.classList.toggle('text-gray-400', !hasLiked);
                        
                        // Actualizar relleno del icono
                        likeIcon.setAttribute('fill', hasLiked ? 'currentColor' : 'none');
                        
                        // Actualizar el conteo
                        let currentCount = parseInt(likeCountSpan.textContent, 10);
                        likeCountSpan.textContent = hasLiked ? currentCount + 1 : currentCount - 1;
                    }

                } catch (error) {
                    console.error('Error al dar Tarjeta Verde:', error);
                } finally {
                    // Volvemos a habilitar el botón después de 1/4 de segundo
                    setTimeout(() => {
                        likeButton.disabled = false;
                    }, 250);
                }
            });
        }
    }
})();
</script>

<style>
/* Ocultar scrollbars en navegadores comunes */
.no-scrollbar::-webkit-scrollbar{ display:none; }
.no-scrollbar{ -ms-overflow-style:none; scrollbar-width:none; }
</style>

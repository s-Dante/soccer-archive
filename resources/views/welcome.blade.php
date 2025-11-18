<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Soccer Archive - Historia de los Mundiales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Ocultar barra de scroll pero mantener funcionalidad */
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Efecto de brillo al pasar el mouse */
        .card-hover-effect::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-25deg);
            transition: 0.5s;
            pointer-events: none;
        }
        .card-container:hover .card-hover-effect::after {
            left: 150%;
            transition: 0.7s ease-in-out;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex flex-col font-sans selection:bg-blue-500 selection:text-white">
    
    <x-header />

    <main class="flex-1 flex flex-col justify-center relative overflow-hidden py-10 ">
        
        {{-- Fondo decorativo estático --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-900/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-900/20 rounded-full blur-[120px]"></div>
        </div>

        <div class="container mx-auto px-6 mb-6">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-2 baloo-bhaijaan-2-bold">
                <pre>

                </pre>
            </h2>
        </div>

        {{-- CONTENEDOR DEL CARRUSEL --}}
        <div class="relative w-full group/slider y-5">
            
            {{-- Botón Anterior --}}
            <button id="scrollLeftBtn" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/50 hover:bg-blue-600 text-white p-4 rounded-full backdrop-blur-md border border-white/10 shadow-xl transition-all opacity-0 group-hover/slider:opacity-100 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>

            {{-- LISTA DE TARJETAS (Scroll Container) --}}
            <div id="slider" class="flex overflow-x-auto gap-8 px-6 md:px-12 py-12 hide-scrollbar scroll-smooth snap-x snap-mandatory">
                
                @forelse($worldCups as $wc)
                    @php
                        $coverImage = $wc->cover_image 
                            ? 'data:image/jpeg;base64,' . base64_encode($wc->cover_image) 
                            : asset('images/logo.png');
                        
                        $ballImage = $wc->ball_image 
                            ? 'data:image/jpeg;base64,' . base64_encode($wc->ball_image) 
                            : asset('images/logo.png');
                    @endphp

                    {{-- TARJETA INDIVIDUAL --}}
                    <a href="{{ route('worldcup.show', $wc->year) }}" 
                       class="card-container relative flex-shrink-0 w-[280px] h-[420px] md:w-[320px] md:h-[480px] rounded-2xl cursor-pointer group snap-center transition-all duration-300 hover:-translate-y-4">
                        
                        {{-- 1. Contenedor de Imagen (Portada) --}}
                        <div class="w-full h-full rounded-2xl overflow-hidden relative shadow-2xl border border-white/10 card-hover-effect bg-zinc-900">
                            <img src="{{ $coverImage }}" alt="Mundial {{ $wc->year }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100" />
                            
                            {{-- Overlay Gradiente --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                            
                        </div>

                        {{-- 2. El Balón (Flotante "Pop-out") --}}
                        {{-- Se posiciona absoluto sobre la tarjeta, un poco salido por la derecha --}}
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 md:w-40 md:h-40 z-10 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12 drop-shadow-2xl filter">
                             <img src="{{ $ballImage }}" alt="Balón {{ $wc->year }}" class="w-full h-full object-contain" />
                        </div>

                    </a>

                @empty
                    <div class="w-full text-center py-20">
                        <p class="text-zinc-500 text-xl">No hay mundiales disponibles.</p>
                    </div>
                @endforelse

                {{-- Espaciador final para que el último elemento no quede pegado --}}
                <div class="w-4 flex-shrink-0"></div>

            </div>

            {{-- Botón Siguiente --}}
            <button id="scrollRightBtn" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/50 hover:bg-blue-600 text-white p-4 rounded-full backdrop-blur-md border border-white/10 shadow-xl transition-all opacity-0 group-hover/slider:opacity-100 hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

        </div>

        {{-- Indicador visual (Barra de progreso simple) --}}
        <div class="container mx-auto px-6 mt-4">
            <div class="w-full h-1 bg-zinc-800 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full bg-blue-600 w-0 transition-all duration-300"></div>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('slider');
            const leftBtn = document.getElementById('scrollLeftBtn');
            const rightBtn = document.getElementById('scrollRightBtn');
            const progressBar = document.getElementById('progressBar');

            // Cantidad de scroll al hacer click (aprox el ancho de una tarjeta + gap)
            const scrollAmount = 350; 

            leftBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            // Actualizar barra de progreso al hacer scroll
            slider.addEventListener('scroll', () => {
                // Cálculo del porcentaje de scroll
                const maxScroll = slider.scrollWidth - slider.clientWidth;
                const percentage = (slider.scrollLeft / maxScroll) * 100;
                progressBar.style.width = `${percentage}%`;
            });
        });
    </script>
</body>
</html>
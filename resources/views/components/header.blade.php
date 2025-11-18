{{-- Estilos para el efecto glass (se queda igual) --}}
<style>
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
</style>

{{-- Controlamos SÓLO el menú móvil. 'profileOpen' ya no es necesario. --}}
{{-- 1. Quitamos @click.away de aquí --}}
<header x-data="{ mobileOpen: false }" class="relative w-full flex justify-between items-center py-4 px-6 lg:px-20 z-50 text-white">
    
    {{-- BUSCAR (Izquierda) --}}
    <a href="{{ route('search.index') }}" class="baloo-bhaijaan-2-medium block glass hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
        <pre>Buscar     🔍</pre>
    </a>

    <!-- <div class="flex-1">
        {{-- Quitamos el <form> y lo reemplazamos por un <a> simple --}}
        {{-- Esto ahora es un botón que LLEVA a la página de búsqueda --}}
        <a href="{{ route('search.index') }}" 
           class="hidden lg:flex glass items-center p-1 w-full max-w-sm hover:bg-white/10 transition-colors rounded-full cursor-pointer">
            
            {{-- Texto "Buscar..." que simula el input --}}
            <span class="flex-grow bg-transparent text-neutral-300 py-2 px-4 baloo-bhaijaan-2-regular">Buscar...</span>
            
            {{-- El botón/icono --}}
            <div class="baloo-bhaijaan-2-medium cursor-pointer bg-white/10 rounded-full py-2 px-5">
                <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </a>
        <a href="{{ route('search.index') }}" class="lg:hidden p-2">
            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </a>
    </div> -->

    {{-- LOGO (Centro) --}}
    <div class="lg:absolute lg:left-1/2 lg:top-1/2 lg:-translate-x-1/2 lg:-translate-y-1/2">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo de Soccer Archive" class="h-16 lg:h-20 w-auto transition-transform hover:scale-105" />
        </a>
    </div>

    {{-- NAVEGACIÓN (Derecha) --}}
    <div class="flex-1 flex justify-end">
        {{-- Menú de Escritorio --}}
        <nav class="hidden lg:flex items-center">
             @guest
                {{-- Menú de Invitado (Escritorio) --}}
                <div class="glass p-1 max-w-sm w-full">
                    <ul class="flex justify-around items-center w-full">
                        <li><a href="{{ route('auth.login') }}" class="baloo-bhaijaan-2-medium block hover:bg-white/20 rounded-full py-3 px-6 transition-colors">Log In</a></li>
                        <li><a href="{{ route('auth.register') }}" class="baloo-bhaijaan-2-medium block bg-white/10 hover:bg-white/20 rounded-full py-3 px-6 transition-colors">Sign Up</a></li>
                    </ul>
                </div>
            @endguest
            
            @auth
                {{-- Menú de Perfil (Escritorio) con botones separados --}}
                <div class="flex items-center gap-4">
                    
                    {{-- Botón Contribuir (con estilo glass) --}}
                    <a href="{{ route('user.contribute') }}" class="baloo-bhaijaan-2-medium block glass hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
                        Contribuir
                    </a>

                    {{-- Botón Cerrar Sesión (con estilo glass) --}}
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="baloo-bhaijaan-2-medium block glass hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
                            Cerrar Sesión
                        </button>
                    </form>

                    {{-- Foto de Perfil (como enlace directo) --}}
                    <a href="{{ route('user.me') }}" class="flex rounded-full glass text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        @auth
                            @if (Auth::user()->profile_photo)
                                    <span class="sr-only">Ir a mi perfil</span>
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                        src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->profile_photo) }}" 
                                        alt="Foto de perfil">
                            @else
                                <div class="w-12 h-12 rounded-full object-cover bg-gray-700 flex items-center justify-center">
                                    <span class="text-4xl text-gray-400">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        @endauth
                    </a>
                </div>
            @endauth
        </nav>

        {{-- Botón de Hamburguesa (Móvil) --}}
        <div class="lg:hidden">
            <button @click="mobileOpen = !mobileOpen" class="text-white focus:outline-none p-2">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Menú Móvil (se muestra al hacer clic en la hamburguesa) --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lg:hidden absolute top-full left-0 w-full mt-2"
         >
        {{-- 2. Quitamos la clase 'hidden' y el 'id' de aquí --}}
        <div class="bg-gray-800/95 backdrop-blur-sm shadow-lg rounded-b-lg p-4 mx-4">
            <ul class="flex flex-col items-center space-y-4">
                @guest
                    <li><a href="{{ route('auth.login') }}" class="baloo-bhaijaan-2-medium block py-2">Log In</a></li>
                    <li><a href="{{ route('auth.register') }}" class="baloo-bhaijaan-2-medium block py-2">Sign Up</a></li>
                @endguest
                @auth
                    {{-- Enlaces de usuario para el menú móvil --}}
                    <li><a href="{{ route('user.me') }}" class="baloo-bhaijaan-2-medium block py-2">Mi Perfil</a></li>
                    <li><a href="{{ route('user.contribute') }}" class="baloo-bhaijaan-2-medium block py-2">Contribuir</a></li>
                    <li><a href="{{ route('user.settings') }}" class="baloo-bhaijaan-2-medium block py-2">Ajustes</a></li>
                    <li>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-center baloo-bhaijaan-2-medium block py-2">
                                Cerrar Sesión
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</header>
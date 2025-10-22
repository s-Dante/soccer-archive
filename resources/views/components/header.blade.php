{{-- Estilos para el efecto glass (se queda igual) --}}
<style>
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
</style>

<header class="relative w-full flex justify-between items-center py-4 px-6 lg:px-20 z-50">
    
    <div class="flex-1">
        <form action="{{-- route('search.index') --}}" method="GET" class="hidden lg:flex glass items-center p-1 w-full max-w-sm">
            <input type="text" name="search" placeholder="Buscar..." class="flex-grow bg-transparent text-white placeholder-neutral-300 outline-none baloo-bhaijaan-2-regular py-2 px-4" />
            <button type="submit" class="baloo-bhaijaan-2-medium cursor-pointer bg-white/10 hover:bg-white/20 rounded-full py-2 px-5 transition-colors">
                Buscar
            </button>
        </form>
        <a href="{{-- route('search.index') --}}" class="lg:hidden p-2">
            <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </a>
    </div>

    <div class="lg:absolute lg:left-1/2 lg:top-1/2 lg:-translate-x-1/2 lg:-translate-y-1/2">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo de Soccer Archive" class="h-16 lg:h-20 w-auto transition-transform hover:scale-105" />
        </a>
    </div>

    <div class="flex-1 flex justify-end">
        <nav class="hidden lg:flex glass p-1 max-w-sm w-full">
             <ul class="flex justify-around items-center w-full">
                @guest
                    <li><a href="{{ route('auth.login') }}" class="baloo-bhaijaan-2-medium block hover:bg-white/20 rounded-full py-3 px-6 transition-colors">Log In</a></li>
                    <li><a href="{{ route('auth.register') }}" class="baloo-bhaijaan-2-medium block bg-white/10 hover:bg-white/20 rounded-full py-3 px-6 transition-colors">Sign Up</a></li>
                @endguest
                @auth
                    {{-- Tus enlaces de usuario autenticado aquí --}}
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="baloo-bhaijaan-2-medium w-full ...">
                            Salir
                        </button>
                    </form>
                @endauth
            </ul>
        </nav>
        <div class="lg:hidden">
            <button id="hamburger-button" class="text-white focus:outline-none p-2">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden absolute top-full left-0 w-full mt-2">
        <div class="bg-gray-800/95 backdrop-blur-sm shadow-lg rounded-b-lg p-4 mx-4">
            <ul class="flex flex-col items-center space-y-4">
                @guest
                    <li><a href="{{ route('auth.login') }}" class="baloo-bhaijaan-2-medium block py-2">Log In</a></li>
                    <li><a href="{{ route('auth.register') }}" class="baloo-bhaijaan-2-medium block py-2">Sign Up</a></li>
                @endguest
                @auth
                    {{-- Tus enlaces de usuario autenticado para el menú móvil aquí --}}
                @endauth
            </ul>
        </div>
    </div>
</header>
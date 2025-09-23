{{-- Definimos una clase 'glass' reutilizable para el efecto de cristal --}}
<style>
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
</style>

<header class="relative w-full flex justify-between items-center py-4 px-6 md:px-20">
    
    <div class="w-80">
        <form action="{{--  --}}" method="GET" class="glass flex items-center p-1">
            <input type="text" name="search" placeholder="🔍" 
                   class="flex-grow bg-transparent text-white placeholder-neutral-300 outline-none baloo-bhaijaan-2-regular py-2 px-4" />
            <button type="submit" 
                    class="baloo-bhaijaan-2-medium cursor-pointer bg-white/10 hover:bg-white/20 rounded-full py-2 px-5 transition-colors">
                Buscar
            </button>
        </form>
    </div>

    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo de Soccer Archive" class="h-20 w-auto transition-transform hover:scale-105" />
        </a>
    </div>

    <div class="w-80">
        <nav class="glass p-1">
            <ul class="flex justify-around items-center">
                @guest
                    <li>
                        <a href="{{ route('auth.login') }}" class="baloo-bhaijaan-2-medium block hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
                            Log In
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('auth.register') }}" class="baloo-bhaijaan-2-medium block bg-white/10 hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
                            Sign Up
                        </a>
                    </li>
                @endguest

                @auth
                    <li>
                        <a href="{{-- route('user.me') --}}" class="baloo-bhaijaan-2-medium block hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
                            Perfil
                        </a>
                    </li>
                    <li>
                        <form action="{{-- route('auth.logout') --}}" method="POST">
                            @csrf
                            <button type="submit" class="baloo-bhaijaan-2-medium w-full text-left bg-white/10 hover:bg-white/20 rounded-full py-3 px-6 transition-colors">
                                Salir
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>
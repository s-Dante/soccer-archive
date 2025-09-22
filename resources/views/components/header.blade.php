<header class="py-4 px-20 w-full flex justify-between items-center">

    <!-- Barra de Busqueda -->
    <div class="bg-neutral-400/30 backdrop-blur-[1px] border border-neutral-400/20 rounded-full px-4 py-2">
        <form action="{{ route('home') }}" method="GET" >
            <input type="text" name="search" placeholder="Buscar..." class="bg-transparent outline-none baloo-bhaijaan-2-regular" />
            <button type="submit" class="baloo-bhaijaan-2-medium">Buscar</button>
        </form>
    </div>


    <!-- Logotipo -->
    <div class="flex items-center">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo de Soccer Archive" class="h-20 w-auto" />
        </a>
    </div>

    <!-- Navegacion por la pagina-->
    <nav class="bg-neutral-400/30 backdrop-blur-[1px] border border-neutral-400/20 rounded-full py-2 px-6">
        <ul class="flex space-x-5">
            @guest
                <li><a href="{{ route('auth.login') }}" class="baloo-bhaijaan-2-medium bg-transparent hover:bg-neutral-400/20 rounded-full py-2 px-4">Log In</a></li>
                <li><a href="{{ route('auth.register') }}" class="baloo-bhaijaan-2-medium bg-transparent hover:bg-neutral-400/20 rounded-full py-2 px-4">Sign Up</a></li>
            @endguest

            @auth
                <li>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit">Log Out</button>
                    </form>
                </li>
                <li><a href="{{ route('me') }}">mi perfil</a></li>
            @endauth
        </ul>
    </nav>
</header>

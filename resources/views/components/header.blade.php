<header class="bg-gray-800 text-white p-4 flex justify-between items-center">
  <div>
    <a href="{{ route('home') }}" class="font-bold text-xl">MiProyecto</a>
  </div>

  <nav>
    <ul class="flex space-x-4">
      <li><a href="{{ route('home') }}">Inicio</a></li>
      <li><a href="{{ url('/about') }}">Acerca</a></li>

      @guest
        <li><a href="{{ route('login.auth') }}">Iniciar Sesión</a></li>
        <li><a href="{{ route('register.auth') }}">Registrarse</a></li>
      @endguest

      @auth
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Cerrar Sesión</button>
          </form>
        </li>
      @endauth
    </ul>
  </nav>
</header>

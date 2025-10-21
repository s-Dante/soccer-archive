<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans">

    {{-- Capa Overlay para cerrar el menú en móvil --}}
    <div id="sidebar-overlay" class="hidden lg:hidden fixed inset-0 bg-black/50 z-40"></div>

    <div class="flex min-h-screen">
        {{-- SIDEBAR DE NAVEGACIÓN --}}
        <aside id="admin-sidebar" class="w-64 bg-gray-800 shadow-md p-6 fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50">
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-2">
                    <h2 class="text-xl font-bold text-gray-300">Admin Panel</h2>
                </a>
            </div>
            
            <nav class="space-y-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📊</span> Dashboard</a>
                <a href="{{ route('admin.publications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📄</span> Publicaciones</a>
                <a href="{{ route('admin.worldcups.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🏆</span> Mundiales</a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🗂️</span> Categorías</a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>👥</span> Usuarios</a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>💬</span> Comentarios</a>
            </nav>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="flex-1 lg:mx-8">
             {{-- Este header solo es visible en pantallas pequeñas y medianas --}}
            <header class="lg:hidden sticky top-0 flex justify-between items-center bg-gray-800 p-4 shadow-md z-30">
                <h2 class="text-lg font-bold red-500">@yield('title', 'Admin Panel')</h2>
                <div></div> {{-- Div vacío para centrar el título --}}
                <button id="admin-sidebar-toggle" class="text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </header>

            <main class="p-6 md:p-10">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>

<!-- 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans overflow-hidden"> {{-- Evitamos el scroll en el body --}}

    {{-- Capa Overlay para cerrar el menú en móvil --}}
    <div id="sidebar-overlay" class="hidden lg:hidden fixed inset-0 bg-black/50 z-40"></div>

    <div class="flex h-screen">
        {{-- SIDEBAR DE NAVEGACIÓN (Comportamiento Híbrido) --}}
        <aside id="admin-sidebar" 
               class="w-64 bg-gray-800 shadow-md p-6 fixed lg:static inset-y-0 
                      right-0 lg:left-0 {{-- Posición base: DERECHA, en LG: IZQUIERDA --}}
                      transform translate-x-full lg:translate-x-0 {{-- Sale por la DERECHA, en LG: se queda quieto --}}
                      transition-transform duration-300 ease-in-out z-50">
            
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-2 hidden lg:block">
                    <h2 class="text-xl font-bold text-gray-300">Admin Panel</h2>
                </a>
            </div>
            
            <nav class="space-y-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📊</span> Dashboard</a>
                <a href="{{ route('admin.publications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📄</span> Publicaciones</a>
                <a href="{{ route('admin.worldcups.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🏆</span> Mundiales</a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🗂️</span> Categorías</a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>👥</span> Usuarios</a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>💬</span> Comentarios</a>
            </nav>
        </aside>

        {{-- CONTENEDOR PRINCIPAL --}}
        <div class="flex-1 flex flex-col lg:ml-64"> {{-- Margen izquierdo SOLO en pantallas grandes --}}
            
            {{-- HEADER MÓVIL (Fijo y sin scroll) --}}
            <header class="lg:hidden flex justify-between items-center bg-gray-800 p-4 shadow-md z-30 flex-shrink-0 w-[100dvw]">
                <a href="{{ route('home') }}" class="block lg:hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                </a>
                <button id="admin-sidebar-toggle" class="text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </header>

            {{-- Área de contenido con su PROPIO scroll independiente --}}
            <div class="flex-grow">
                <main class="p-6 md:p-10">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

</body>
</html> -->


<!-- 


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans">

    {{-- Capa Overlay para cerrar el menú en móvil --}}
    <div id="sidebar-overlay" class="hidden lg:hidden fixed inset-0 bg-black/50 z-40"></div>

    <div class="flex min-h-screen">
        {{-- SIDEBAR DE NAVEGACIÓN (Vuelve a estar a la izquierda) --}}
        <aside id="admin-sidebar" 
               class="w-64 bg-gray-800 shadow-md p-6 fixed lg:static inset-y-0 left-0 
                      transform -translate-x-full lg:translate-x-0 
                      transition-transform duration-300 ease-in-out z-50">
            
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-2">
                    <h2 class="text-xl font-bold text-gray-300">Admin Panel</h2>
                </a>
            </div>
            
            <nav class="space-y-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📊</span> Dashboard</a>
                <a href="{{ route('admin.publications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📄</span> Publicaciones</a>
                <a href="{{ route('admin.worldcups.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🏆</span> Mundiales</a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🗂️</span> Categorías</a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>👥</span> Usuarios</a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>💬</span> Comentarios</a>
            </nav>
        </aside>

        {{-- CONTENIDO PRINCIPAL (Ahora con scroll normal) --}}
        <div class="flex-1 lg:ml-64">
             {{-- Header móvil --}}
            <header class="lg:hidden flex justify-between items-center bg-gray-800 p-4 shadow-md w-[100dvw] sticky top-0 left-0">
                <h2 class="text-lg font-bold">@yield('title', 'Admin Panel')</h2>
                <button id="admin-sidebar-toggle" class="text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </header>

            <main class="p-6 md:p-10">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html> -->

<!-- 

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans">

    {{-- Capa Overlay para cerrar el menú en móvil --}}
    <div id="sidebar-overlay" class="hidden lg:hidden fixed inset-0 bg-black/50 z-40"></div>

    <div class="flex min-h-screen">
        {{-- SIDEBAR DE NAVEGACIÓN (Desde la izquierda) --}}
        <aside id="admin-sidebar" 
               class="w-64 bg-gray-800 shadow-md p-6 fixed lg:static inset-y-0 left-0 
                      transform -translate-x-full lg:translate-x-0 
                      transition-transform duration-300 ease-in-out z-50">
            
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-2">
                    <h2 class="text-xl font-bold text-gray-300">Admin Panel</h2>
                </a>
            </div>
            
            <nav class="space-y-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📊</span> Dashboard</a>
                <a href="{{ route('admin.publications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>📄</span> Publicaciones</a>
                <a href="{{ route('admin.worldcups.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🏆</span> Mundiales</a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>🗂️</span> Categorías</a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>👥</span> Usuarios</a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition"><span>💬</span> Comentarios</a>
            </nav>
        </aside>

        {{-- CONTENIDO PRINCIPAL (Con scroll normal) --}}
        <div class="flex-1 lg:ml-64">
             {{-- Header móvil --}}
            <header class="lg:hidden flex justify-between items-center bg-gray-800 p-4 shadow-md sticky top-0">
                <h2 class="text-lg font-bold">@yield('title', 'Admin Panel')</h2>
                <button id="admin-sidebar-toggle" class="text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </header>

            <main class="p-6 md:p-10">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html> -->
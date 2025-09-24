<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans">

    <div class="flex min-h-screen">
        {{-- SIDEBAR DE NAVEGACIÓN --}}
        <aside class="w-64 bg-gray-800 shadow-md p-6">
            <div class="mb-8 text-center">
                <a href="{{-- route('home') --}}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-2">
                    <h2 class="text-xl font-bold text-gray-300">Admin Panel</h2>
                </a>
            </div>
            
            <nav class="space-y-3">
                <a href="{{-- route('admin.dashboard') --}}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{-- route('admin.publications.index') --}}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <span>📄</span> Publicaciones
                </a>
                <a href="{{-- route('admin.worldcups.index') --}}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <span>🏆</span> Mundiales
                </a>
                <a href="{{-- route('admin.categories.index') --}}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <span>🗂️</span> Categorías
                </a>
                <!-- <a href="{{-- route('admin.users.index') --}}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <span>👥</span> Usuarios
                </a> -->
                <a href="{{-- route('admin.comments.index') --}}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <span>💬</span> Comentarios
                </a>
            </nav>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="flex-1 p-10">
            @yield('content')
        </main>
    </div>

</body>
</html>
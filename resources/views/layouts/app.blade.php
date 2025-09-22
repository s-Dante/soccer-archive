<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Soccer Archive')</title>
  <meta name="description" content="@yield('meta_description', 'Explora Soccer Archive')">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head') {{-- para estilos/metas adicionales por página --}}
</head>
<body class="@yield('body_class', 'min-h-screen bg-white')">
  <x-header />

  <main class="mx-auto max-w-6xl px-4 py-10">
    @yield('content')
  </main>

  @stack('scripts') {{-- para scripts de cada página --}}
</body>
</html>

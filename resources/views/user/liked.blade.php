@extends('layouts.app') {{-- Usamos el layout principal --}}

@section('title', 'Mis Tarjetas Verdes')

@section('content')
<div class="container mx-auto px-4 py-12 text-white">

    {{-- Encabezado de la Página --}}
    <div class="mb-12">
        <a href="{{ route('user.me') }}" class="text-sm text-blue-400 hover:underline">&larr; Volver a mi perfil</a>
        <h1 class="text-4xl font-bold baloo-bhaijaan-2-bold mt-2">Mis Tarjetas Verdes</h1>
        <p class="text-lg text-gray-300">Todas las publicaciones que has marcado con una tarjeta verde.</p>
    </div>

    {{-- --- SECCIÓN DE PUBLICACIONES --- --}}
    
    @if(empty($publications))
        <div class="text-center text-gray-400 py-16">
            <p class="text-xl">Aún no has dado ninguna "Tarjeta Verde".</p>
            <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-400 hover:underline text-lg">¡Explora las publicaciones!</a>
        </div>
    @else
        {{-- Grid "Estilo Instagram" --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
            
            @foreach($publications as $publication)
                {{-- 
                    Reutilizamos el componente.
                    Le pasamos las variables que el CONSTRUCTOR espera:
                    - El parámetro $details espera :details
                    - El parámetro $media espera :media
                --}}
                <x-publication-card 
                    :details="$publication" 
                    :media="$media->get($publication->id, collect())"
                    :show-status="false" {{-- No mostramos el estado (Aprobado, etc.) aquí --}}
                />
            @endforeach

        </div>
    @endif

</div>
@endsection
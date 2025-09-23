<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soccer Archive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-zinc-950 text-zinc-100">
    <x-header />

    @php
        $wc = [
            [
                'year' => 1930,
                'cover' => asset('images/MundialCovers/1930.jpeg'),
                'ball' => asset('images/MundialBalls/1930.png'),
            ],
            [
                'year' => 1934,
                'cover' => asset('images/MundialCovers/1934.jpeg'),
                'ball' => asset('images/MundialBalls/1934.png'),
            ],
            [
                'year' => 1938,
                'cover' => asset('images/MundialCovers/1938.jpeg'),
                'ball' => asset('images/MundialBalls/1938.png'),
            ],
            [
                'year' => 1950,
                'cover' => asset('images/MundialCovers/1950.jpeg'),
                'ball' => asset('images/MundialBalls/1950.png'),
            ],
            [
                'year' => 1954,
                'cover' => asset('images/MundialCovers/1954.jpeg'),
                'ball' => asset('images/MundialBalls/1954.png'),
            ],
            [
                'year' => 1962,
                'cover' => asset('images/MundialCovers/1962.jpeg'),
                'ball' => asset('images/MundialBalls/1962.png'),
            ],
            [
                'year' => 1966,
                'cover' => asset('images/MundialCovers/1966.jpeg'),
                'ball' => asset('images/MundialBalls/1966.png'),
            ],
            [
                'year' => 1970,
                'cover' => asset('images/MundialCovers/1970.jpeg'),
                'ball' => asset('images/MundialBalls/1970.png'),
            ],
            [
                'year' => 1974,
                'cover' => asset('images/MundialCovers/1974.jpeg'),
                'ball' => asset('images/MundialBalls/1974.png'),
            ],
        ];
    @endphp

    <main class="container mx-auto mt-8">
        <h2 class="text-3xl font-bold text-white mb-6 text-center">Copas Mundiales de la FIFA</h2>

        <div class="relative">
            <div id="carousel" class="flex overflow-x-auto scroll-smooth scrollbar-hide">

                {{-- Bucle dinámico usando el componente --}}
                @forelse ($worldCups as $wc)
                    <x-world-cup-card :worldCup="$wc" />
                @empty
                    <p class="text-white text-center w-full">No hay mundiales para mostrar.</p>
                @endforelse

            </div>

            {{-- Botones del carrusel (si los tienes) --}}
            <button id="prev"
                class="absolute left-0 top-1/2 -translate-y-1/2 bg-gray-800 p-2 rounded-full">&lt;</button>
            <button id="next"
                class="absolute right-0 top-1/2 -translate-y-1/2 bg-gray-800 p-2 rounded-full">&gt;</button>
        </div>
    </main>

</body>

</html>

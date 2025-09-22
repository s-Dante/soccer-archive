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

    <main
        class="mx-auto max-w-[92dvw] h-[100dvh] px-4 py-10 bg-gradient-to-b from-zinc-800 from-5% to-stone-950 rounded-t-[50px] ">
        <div
            class="h-[100dvh] w-[98%] bg-gradient-to-b from-stone-950 from-5% to-zinc-800 rounded-t-[25px] mx-auto p-10">
            <section id="wc-carousel" class="relative">

                {{-- Controles --}}
                <button type="button" data-action="prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 rounded-md bg-zinc-800 px-3 py-2 z-10 cursor-pointer hover:bg-zinc-700">
                    "<"
                </button>

                <button type="button" data-action="next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 rounded-md bg-zinc-800 px-3 py-2 z-10 cursor-pointer hover:bg-zinc-700">
                    ">"
                </button>

                {{-- Escenario --}}
                <div class="relative h-[500px] align-middle">
                    {{-- Arco de tarjetas generado por Blade --}}
                    <div id="wc-arc" class="absolute inset-x-0 top-6 h-[600px] m-y-50">
                        {{-- Tarjetas generadas por Blade --}}
                        {{-- Nota: el indice es importante para la logica del carrusel --}}
                        @foreach ($wc as $idx => $item)
                            <button
                                class="wc-thumb absolute m-x-50"
                                data-index="{{ $idx }}" data-year="{{ $item['year'] }}"
                                data-cover="{{ $item['cover'] }}" data-ball="{{ $item['ball'] }}"
                                aria-label="Ir a {{ $item['year'] }}" type="button">
                                <img src="{{ $item['cover'] }}" alt="Mundial {{ $item['year'] }}"
                                    class="h-[300px] w-auto rounded block rounded-[18px]">
                            </button>
                        @endforeach
                    </div>

                    {{-- Balón sincronizado --}}
                    <img id="wc-ball" alt=""
                        class="pointer-events-none select-none align-self-end justify-self-center absolute w-[800px] top-[60%] animate-[spin_250s_linear_infinite]">
                </div>
            </section>
        </div>
    </main>
</body>

</html>

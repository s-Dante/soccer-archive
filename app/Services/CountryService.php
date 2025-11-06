<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CountryService
{
    /**
     * Obtiene una lista de todos los países desde una API externa.
     * Los resultados se guardan en caché por 30 días para evitar llamadas innecesarias.
     */
    public function getCountryList(): array
    {
        // Usamos la caché para no llamar a la API en cada carga
        return Cache::remember('countries.list', now()->addDays(30), function () {
            try {
                $response = Http::get('https://restcountries.com/v3.1/all?fields=name');

                if ($response->failed()) {
                    return ['Error: No se pudo conectar a la API'];
                }

                $countries = $response->json();
                
                // Extraemos el nombre común y lo ordenamos alfabéticamente
                return collect($countries)
                    ->pluck('name.common')
                    ->sort()
                    ->values()
                    ->all();

            } catch (\Exception $e) {
                return ['Error: ' . $e->getMessage()];
            }
        });
    }
}

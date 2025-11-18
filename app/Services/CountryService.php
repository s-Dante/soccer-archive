<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CountryService
{
    protected $baseUrl = 'https://restcountries.com/v3.1';

    public function getAllCountries(): array
    {
        try {
            // Hacemos la petición a la API de REST Countries
            $response = Http::timeout(5)->get("{$this->baseUrl}/all", [
                'fields' => 'name,cca2' // Seleccionar solo los campos necesarios (ISO Code y Nombre)
            ]);

            if ($response->successful()) {
                $countries = $response->json();
                $list = [];
                
                foreach ($countries as $country) {
                    // Usamos el código ISO como clave y el nombre común como valor
                    $list[$country['cca2']] = $country['name']['common'];
                }
                
                // Ordenar alfabéticamente por nombre
                asort($list);
                return $list;
            }
        } catch (\Exception $e) {
            // Fallback en caso de error de conexión o API
             \Log::error('Error al conectar con REST Countries API: ' . $e->getMessage());
        }

        return ['MX' => 'México', 'US' => 'Estados Unidos']; // Fallback manual de emergencia
    }
    
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

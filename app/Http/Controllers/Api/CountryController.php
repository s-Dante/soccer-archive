<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CountryService; // Importar el servicio que acabamos de modificar
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Retorna la lista de países como JSON para ser usada por AJAX.
     */
    public function index()
    {
        // Llama al servicio que obtiene los datos de la API externa
        $countries = $this->countryService->getAllCountries();

        // Devolver la lista como JSON
        return response()->json($countries);
    }
}
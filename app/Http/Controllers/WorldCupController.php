<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ¡Importante! Para usar la base de datos

class WorldCupController extends Controller
{
    /**
     * Muestra la lista de todos los mundiales (la página principal).
     */
    public function index()
    {
        // Llamamos a un Stored Procedure que nos devuelve todos los mundiales
        $worldCups = DB::select('CALL sp_get_all_world_cups()');

        // Pasamos los datos a la vista 'welcome.blade.php'
        return view('welcome', ['worldCups' => $worldCups]);
    }

    /**
     * Muestra los detalles de un mundial específico.
     */
    public function show(string $year)
    {
        // Llamamos a un SP para obtener un mundial por su año
        $results = DB::select('CALL sp_get_world_cup_by_year(?)', [$year]);

        // DB::select siempre devuelve un array, tomamos el primer resultado
        $worldCup = $results[0] ?? null;

        // Si no se encontró el mundial, redirigimos al inicio
        if (!$worldCup) {
            abort(404);
        }

        // Llamamos a otro SP para obtener las publicaciones de ese mundial
        $publications = DB::select('CALL sp_get_publications_by_world_cup(?)', [$worldCup->id]);

        // Pasamos los datos del mundial y sus publicaciones a la vista
        return view('world-cup', [
            'worldCup' => $worldCup,
            'publications' => $publications
        ]);
    }

    // Los otros métodos (create, store, etc.) los llenaremos después
}
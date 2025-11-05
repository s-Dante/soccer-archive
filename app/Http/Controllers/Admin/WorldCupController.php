<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Asegúrate de que esto esté importado

class WorldCupController extends Controller
{
    /**
     * Muestra la lista de todos los mundiales (admin/worldcups/index).
     * ¡ESTE ES EL MÉTODO QUE CAUSA EL ERROR!
     */
    public function index()
    {
        // 1. Obtener los datos de los mundiales para el carrusel
        $worldCups = DB::select('CALL sp_get_all_world_cups()');
        
        // 2. Simplemente mostrar la vista
        // (Sin ninguna redirección basada en el rol)
        return view('welcome', ['worldCups' => $worldCups]);
    }

    /**
     * Muestra el formulario para crear un nuevo mundial (admin/worldcups/create).
     */
    public function create()
    {
        // Este método no pasa ninguna variable, por eso la vista 'create' no da error.
        return view('admin.worldcups.create');
    }

    /**
     * Guarda el nuevo mundial en la BD.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'year' => 'required|numeric|unique:world_cups,year',
            'host_country' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
            'ball_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
        ]);

        // 2. Convertir imágenes a BLOB
        $coverImage = $request->hasFile('cover_image') 
                      ? file_get_contents($request->file('cover_image')->getRealPath()) 
                      : null;
        
        $ballImage = $request->hasFile('ball_image') 
                     ? file_get_contents($request->file('ball_image')->getRealPath()) 
                     : null;

        // 3. Llamar al Stored Procedure para insertar
        DB::statement(
            'CALL sp_admin_create_world_cup(?, ?, ?, ?, ?)',
            [
                $request->year,
                $request->host_country,
                $request->description,
                $coverImage,
                $ballImage
            ]
        );

        // 4. Redirigir de vuelta al índice con un mensaje de éxito
        return redirect()->route('admin.worldcups.index')->with('success', 'Mundial creado exitosamente.');
    }
    
    public function show(string $year)
    {
        // ... tu lógica para mostrar un mundial ...
        $results = DB::select('CALL sp_get_world_cup_by_year(?)', [$year]);
        $worldCup = $results[0] ?? null;

        if (!$worldCup) {
            abort(404);
        }
        
        $publications = DB::select('CALL sp_get_publications_by_world_cup(?)', [$worldCup->id]);

        return view('world-cup', [
            'worldCup' => $worldCup,
            'publications' => $publications
        ]);
    }

    // ... (Aquí irán los métodos edit, update, destroy) ...
}

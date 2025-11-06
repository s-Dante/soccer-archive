<?php

namespace App\Http\Controllers;

use App\Repositories\WorldCupRepository;
use Illuminate\Http\Request;

class WorldCupController extends Controller
{
    protected $repository;

    public function __construct(WorldCupRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Página de inicio (Home): Muestra todos los mundiales.
     */
    public function index()
    {
        // --- CAMBIO AQUÍ ---
        // Ahora llama al método público explícito
        $worldCups = $this->repository->getForPublicIndex();
        // ------------------
        
        return view('welcome', compact('worldCups'));
    }

    /**
     * Muestra un mundial específico por su año.
     */
    public function show($year)
    {
        // Esto ya estaba bien
        $worldCup = $this->repository->getByYear($year);

        if (!$worldCup) {
            abort(404);
        }

        // Aquí deberíamos cargar también las publicaciones de este mundial
        // $publications = $this->repository->getPublicationsForWorldCup($worldCup->id);
        
        // return view('world-cup', compact('worldCup', 'publications'));
        return view('world-cup', compact('worldCup'));
    }
}

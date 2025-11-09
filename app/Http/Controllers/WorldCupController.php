<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\WorldCupRepository;
use App\Repositories\PublicationRepository;
use App\Repositories\CommentRepository;

class WorldCupController extends Controller
{
    protected $repository;
    protected $publicationRepository;
    protected $commentRepository;

    public function __construct(WorldCupRepository $repository, PublicationRepository $publicationRepository, CommentRepository $commentRepository)
    {
        $this->repository = $repository;
        $this->publicationRepository = $publicationRepository;
        $this->commentRepository = $commentRepository;
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
    /**
     * Muestra un mundial específico por su año.
     */
    public function show($year)
    {
        // 1. Obtenemos los datos del mundial (esto ya estaba bien)
        $worldCup = $this->repository->getByYear($year);

        if (!$worldCup) {
            abort(404);
        }

        // --- 5. LÓGICA AÑADIDA ---
        // 2. Obtenemos todas las publicaciones APROBADAS y su multimedia
        $data = $this->publicationRepository->getForInfographicPage($worldCup->id);
        $allComments = collect();
        foreach ($data['publications'] as $pub) {
            $commentsForPub = $this->commentRepository->getForPublication($pub->id);
            $allComments = $allComments->merge($commentsForPub);
        }

        // Agrupar por publication_id para acceder fácil en la vista
        $commentsByPublication = $allComments->groupBy('publication_id');

        // 3. Pasamos todo a la vista (worldCup, publications, media)
        return view('world-cup', [
            'worldCup' => $worldCup,
            'publications' => $data['publications'],
            'media' => $data['media'],
            'commentsByPublication' => $commentsByPublication
        ]);
    }
}

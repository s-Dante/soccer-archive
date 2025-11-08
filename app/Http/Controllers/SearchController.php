<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PublicationRepository; // Importamos el repo
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    protected $publicationRepository;

    /**
     * Inyectamos el repositorio que tiene todos nuestros métodos.
     */
    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

    /**
     * Muestra la página de búsqueda y los resultados.
     */
    public function index(Request $request)
    {
        // --- 1. DATOS PARA LOS FILTROS ---
        // Reutilizamos el método que ya teníamos para llenar los <select>
        $filterData = $this->publicationRepository->getFormData();
        $worldCups = $filterData['worldCups'];
        $categories = $filterData['categories'];

        // --- 2. DATOS DE LA BÚSQUEDA ---
        // Obtenemos los filtros del formulario (de la URL, ej: /search?category_id=2)
        $filters = $request->only(['category_id', 'world_cup_id', 'host_country', 'author_name']);
        
        // Obtenemos el ID del usuario (o 0 si es invitado)
        $userId = Auth::id() ?? 0;

        // Llamamos al nuevo método del repositorio
        $data = $this->publicationRepository->searchPublications($filters, $userId);

        // --- 3. ENVIAR TODO A LA VISTA ---
        return view('search', [
            'worldCups' => $worldCups,       // Para el <select> de mundiales
            'categories' => $categories,     // Para el <select> de categorías
            'publications' => $data['publications'], // Los resultados
            'media' => $data['media'],           // La multimedia de los resultados
            'filters' => $filters            // Para que el formulario "recuerde" la búsqueda
        ]);
    }
}
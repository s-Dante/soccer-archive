<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication; // Usaremos el modelo para la inyección de ruta
use App\Repositories\PublicationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    protected $repository;

    public function __construct(PublicationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Maneja la acción de dar/quitar "Tarjeta Verde" (Like).
     */
    public function toggleLike(Request $request, Publication $publication)
    {
        $userId = Auth::id(); // Obtenemos el ID del usuario autenticado

        try {
            // Llamamos al repositorio, que llama al SP
            $result = $this->repository->toggleLike($publication->id, $userId);
            
            // Devolvemos una respuesta JSON exitosa
            return response()->json([
                'success' => true,
                'status' => $result->status, // 'liked' o 'unliked'
            ]);

        } catch (\Exception $e) {
            // Si algo falla (ej. la BD), devolvemos un error
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud.'
            ], 500);
        }
    }

    public function searchPublications(array $filters, int $userId)
    {
        // Preparamos los filtros para pasarlos a los SPs
        // Usamos '?? null' para pasar NULL si el filtro no está presente
        $categoryId = $filters['category_id'] ?? null;
        $worldCupId = $filters['world_cup_id'] ?? null;
        $hostCountry = $filters['host_country'] ?? null;
        $authorName = $filters['author_name'] ?? null;

        // 1. Llamamos al SP de publicaciones, pasando el ID del usuario
        $publications = DB::select('CALL sp_search_publications(?, ?, ?, ?, ?)', [
            $categoryId,
            $worldCupId,
            $hostCountry,
            $authorName,
            $userId // El ID del usuario actual para saber sus likes
        ]);

        // 2. Llamamos al SP de multimedia (este no necesita el ID del usuario)
        $allMedia = DB::select('CALL sp_search_publications_media(?, ?, ?, ?)', [
            $categoryId,
            $worldCupId,
            $hostCountry,
            $authorName
        ]);

        // 3. Agrupamos la multimedia por ID de publicación
        $mediaByPublication = collect($allMedia)->groupBy('publication_id');

        return [
            'publications' => $publications,
            'media' => $mediaByPublication
        ];
    }
    
}
<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable; // Importante para manejar errores en la transacción

class PublicationRepository
{
    /**
     * Obtiene los datos necesarios para el formulario de "Contribuir".
     * Llama a los dos SPs que creamos (uno para mundiales, uno para categorías).
     */
    public function getFormData()
    {
        // DB::select devuelve un array de resultados.
        // Como cada SP devuelve un solo conjunto de resultados, esto es seguro.
        $worldCups = DB::select('CALL sp_get_world_cups_for_select()');
        $categories = DB::select('CALL sp_get_categories_for_select()');

        // Devolvemos un array asociativo con ambos resultados
        return [
            'worldCups' => $worldCups,
            'categories' => $categories
        ];
    }

    /**
     * Crea una nueva publicación, incluyendo su multimedia,
     * usando una transacción de base de datos.
     */
    public function createPublication(array $data, int $userId)
    {
        // Usamos una transacción para que, si falla al subir una imagen,
        // tampoco se cree la publicación. Es todo o nada.
        DB::beginTransaction();

        try {
            // --- Paso 1: Crear la publicación principal ---
            $result = DB::selectOne('CALL sp_user_create_publication(?, ?, ?, ?, ?)', [
                $data['title'],
                $data['content'],
                $userId,
                $data['category_id'],
                $data['world_cup_id']
            ]);
            
            // Obtenemos el ID de la publicación que acabamos de crear
            $publicationId = $result->new_publication_id;

            // --- Paso 2: Procesar y guardar las imágenes (si existen) ---
            if (isset($data['images'])) {
                foreach ($data['images'] as $imageFile) {
                    // Convertimos el archivo de imagen a datos binarios (BLOB)
                    $imageData = file_get_contents($imageFile->getRealPath());
                    
                    // Llamamos al SP de multimedia
                    DB::statement('CALL sp_user_add_publication_media(?, ?, ?, ?)', [
                        $publicationId,
                        'image',      // p_media_type
                        $imageData,  // p_media_data (el BLOB)
                        null          // p_media_url (null porque es imagen)
                    ]);
                }
            }

            // --- Paso 3: Procesar y guardar los videos (si existen) ---
            if (isset($data['videos']) && is_array($data['videos']) && count($data['videos']) > 0) {
                foreach ($data['videos'] as $videoUrl) {
                    // Llamamos al SP de multimedia
                    DB::statement('CALL sp_user_add_publication_media(?, ?, ?, ?)', [
                        $publicationId,
                        'video',      // p_media_type
                        null,         // p_media_data (null porque es video)
                        $videoUrl     // p_media_url (el enlace)
                    ]);
                }
            }

            // --- Paso 4: Si todo salió bien, confirmamos los cambios ---
            DB::commit();
            
            return true; // Éxito

        } catch (Throwable $e) {
            // --- Error: Si algo falló, revertimos todo ---
            DB::rollBack();
            
            // Opcional: registrar el error
            // Log::error('Error al crear publicación: ' . $e->getMessage());
            
            return false; // Fracaso
        }
    }

    public function getForAdminIndex()
    {
        $adminUserId = Auth::id() ?? 0;

        return DB::select('CALL sp_admin_get_all_publications(?)', [$adminUserId]);
    }

    /**
     * Obtiene los detalles de UNA publicación (para la página de revisión).
     * Llama a: sp_admin_get_publication_details y sp_admin_get_publication_media
     */
    public function getDetailsById(int $id)
    {
        $adminUserId = Auth::id() ?? 0;

        // El SP de detalles
        $details = DB::select('CALL sp_admin_get_publication_details(?, ?)', [
            $id,
            $adminUserId
        ]);
        
        // El SP de multimedia
        $media = DB::select('CALL sp_admin_get_publication_media(?)', [$id]);

        return [
            'details' => $details[0] ?? null,
            'media' => $media
        ];
    }

    /**
     * Actualiza el estado de una publicación (Aceptada / Rechazada).
     * Llama a: sp_admin_update_publication_status
     */
    public function updateStatus(int $id, string $status): void
    {
        DB::statement('CALL sp_admin_update_publication_status(?, ?)', [
            $id,
            $status
        ]);
    }

    /**
     * Da de baja (Soft Delete) una publicación.
     * Llama a: sp_admin_delete_publication
     */
    public function delete(int $id): void
    {
        DB::statement('CALL sp_admin_delete_publication(?)', [$id]);
    }

    public function getPublicationsForProfile(int $userId)
    {
        $currentUserId = Auth::id() ?? 0;

        $publications = DB::select('CALL sp_get_user_publications(?, ?)', [$userId, $currentUserId]);
        $allMedia = DB::select('CALL sp_get_user_publications_media(?)', [$userId]);

        // Agrupamos la multimedia por 'publication_id' para un acceso fácil en la vista
        $mediaByPublication = collect($allMedia)->groupBy('publication_id');

        return [
            'publications' => $publications,
            'media' => $mediaByPublication
        ];
    }

    /**
     * Obtiene todas las publicaciones APROBADAS y su multimedia
     * para una página de mundial específica (estilo infografía).
     */
    public function getForInfographicPage(int $worldCupId)
    {
        $userId = Auth::id() ?? 0;

        $publications = DB::select('CALL sp_get_infographic_publications(?, ?)', [$worldCupId, $userId]);
        $allMedia = DB::select('CALL sp_get_infographic_media(?)', [$worldCupId]);

        // Agrupamos la multimedia por 'publication_id'
        $mediaByPublication = collect($allMedia)->groupBy('publication_id');

        return [
            'publications' => $publications,
            'media' => $mediaByPublication
        ];
    }

    /**
     * Añade o quita un like. Firma: toggleLike(int $userId, int $publicationId)
     * Devuelve un objeto normalizado: { ok: bool, status: 'liked'|'unliked'|null, raw: <raw SP result>, message?: string }
     */
    public function toggleLike(int $userId, int $publicationId)
    {
        // Llamada al SP con orden userId, publicationId (tal como tu SP espera)
        $res = DB::selectOne('CALL sp_user_toggle_like(?, ?)', [
            $userId,
            $publicationId
        ]);

        if (!$res) {
            return (object)[
                'ok' => false,
                'status' => null,
                'raw' => null,
                'message' => 'No response from stored procedure'
            ];
        }

        // el SP idealmente devuelve: SELECT 'liked' AS status; o 'unliked'
        $status = $res->status ?? $res->result ?? $res->action ?? null;

        return (object)[
            'ok' => true,
            'status' => $status,
            'raw' => $res
        ];
    }


    public function getLikedPublications(int $userId)
    {
        // Nota: Este SP ya trae la multimedia y los detalles de la publicación
        $publications = DB::select('CALL sp_get_user_liked_publications(?)', [$userId]);
        
        // Reutilizamos el SP de multimedia del perfil para ser eficientes
        $allMedia = DB::select('CALL sp_get_user_publications_media(?)', [$userId]);
        $mediaByPublication = collect($allMedia)->groupBy('publication_id');

        return [
            'publications' => $publications,
            'media' => $mediaByPublication
        ];
    }

    public function searchPublications(array $filters, int $userId)
    {
        // Preparamos los filtros para pasarlos a los SPs
        // Usamos '?? null' para pasar NULL si el filtro no está presente
        $categoryId = !empty($filters['category_id']) ? $filters['category_id'] : null;
        $worldCupId = !empty($filters['world_cup_id']) ? $filters['world_cup_id'] : null;
        $hostCountry = !empty($filters['host_country']) ? $filters['host_country'] : null;
        $authorName = !empty($filters['author_name']) ? $filters['author_name'] : null;

        // 1. Llamamos al SP de publicaciones, pasando el ID del usuario
        $publications = DB::select('CALL sp_search_publications(?, ?, ?, ?, ?, ?)', [
            $userId, // El ID del usuario actual para saber sus likes            
            $categoryId,
            $worldCupId,
            $hostCountry,
            $authorName,
            100
        ]);

        // 2. Llamamos al SP de multimedia (este no necesita el ID del usuario)
        $allMedia = DB::select('CALL sp_search_publications_media(?, ?, ?, ?, ?, ?)', [
            $categoryId,
            $worldCupId,
            $hostCountry,
            $authorName,
            100, 
            0
        ]);

        // 3. Agrupamos la multimedia por ID de publicación
        $mediaByPublication = collect($allMedia)->groupBy('publication_id');

        return [
            'publications' => $publications,
            'media' => $mediaByPublication
        ];
    }
}
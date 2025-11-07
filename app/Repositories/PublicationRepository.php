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
            if (isset($data['videos'])) {
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
        return DB::select('CALL sp_admin_get_all_publications()');
    }

    /**
     * Obtiene los detalles de UNA publicación (para la página de revisión).
     * Llama a: sp_admin_get_publication_details y sp_admin_get_publication_media
     */
    public function getDetailsById(int $id)
    {
        // El SP de detalles
        $details = DB::select('CALL sp_admin_get_publication_details(?)', [$id]);
        
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
}
<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentRepository
{
    /**
     * Obtiene todos los comentarios (y sus respuestas) para una publicación.
     * Llama a: sp_get_comments_for_publication
     */
    public function getForPublication(int $publicationId)
    {
        return collect(DB::select('CALL sp_get_comments_for_publication(?)', [$publicationId]));
    }

    /**
     * Añade un nuevo comentario o una respuesta.
     * Llama a: sp_user_add_comment
     * Devuelve el comentario recién creado (gracias al SP).
     */
    public function add(int $publicationId, int $userId, string $content, ?int $parentId = null)
    {
        $res = DB::selectOne('CALL sp_user_add_comment(?, ?, ?, ?)', [
            $publicationId,
            $userId,
            $content,
            $parentId
        ]);

        if (!$res) return null;

        // Normalizar el tipo de author_photo (asegurar que sea string o null)
        $res->author_photo = isset($res->author_photo) ? (string)$res->author_photo : null;

        return $res;
    }


    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE ADMIN (Los añadiremos después)
    |--------------------------------------------------------------------------
    */
    
    /**
     * Obtiene TODOS los comentarios para la lista del admin.
     * Llama a: sp_admin_get_all_comments
     */
    public function getAllForAdmin()
    {
        return DB::select('CALL sp_admin_get_all_comments()');
    }

    /**
     * Da de baja (Soft Delete) un comentario y sus respuestas.
     * Llama a: sp_admin_delete_comment
     */
    public function delete(int $commentId): void
    {
        DB::statement('CALL sp_admin_delete_comment(?)', [$commentId]);
    }

    /**
     * Restaura un comentario y sus respuestas.
     * Llama a: sp_admin_restore_comment
     */
    public function restore(int $commentId): void
    {
        DB::statement('CALL sp_admin_restore_comment(?)', [$commentId]);
    }
}
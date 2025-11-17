<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Definición original (para el down)
        $this->down(); 

        // Nueva definición (up)
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_publications_sort(
                IN p_user_id INT,
                IN p_current_user_id INT,
                IN p_sort_by VARCHAR(50) -- Nuevo parámetro
            )
            BEGIN
                SELECT
                    p.id, p.title, p.content, p.status, p.created_at, p.user_id,
                    c.name AS category_name,
                    w.year AS world_cup_year,
                    w.host_country,
                    -- Usamos las funciones para obtener los conteos
                    fn_get_publication_like_count(p.id) AS like_count,
                    fn_get_publication_comment_count(p.id) AS comment_count,
                    -- Verificamos si el usuario actual le dio like
                    EXISTS (
                        SELECT 1 FROM interactions 
                        WHERE publication_id = p.id 
                          AND user_id = p_current_user_id 
                          AND interaction_type = \'like\'
                    ) AS has_liked
                FROM publications p
                JOIN categories c ON p.category_id = c.id
                JOIN world_cups w ON p.world_cup_id = w.id
                WHERE p.user_id = p_user_id
                  AND p.deleted_at IS NULL

                -- ORDENAMIENTO DINÁMICO
                ORDER BY
                    -- Ordenar por "más likes"
                    CASE WHEN p_sort_by = \'likes_desc\' THEN fn_get_publication_like_count(p.id) END DESC,
                    -- Ordenar por "más comentarios"
                    CASE WHEN p_sort_by = \'comments_desc\' THEN fn_get_publication_comment_count(p.id) END DESC,
                    -- Ordenar por "país (alfabético)"
                    CASE WHEN p_sort_by = \'country_asc\' THEN w.host_country END ASC,
                    -- Orden por defecto (cronológico)
                    p.created_at DESC;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_publications_sort');
        // Re-creamos la versión anterior (sin sorting)
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_publications_sort(
                IN p_user_id INT,
                IN p_current_user_id INT
            )
            BEGIN
                SELECT
                    p.id, p.title, p.content, p.status, p.created_at, p.user_id,
                    c.name AS category_name,
                    w.year AS world_cup_year,
                    EXISTS (
                        SELECT 1 FROM interactions 
                        WHERE publication_id = p.id 
                          AND user_id = p_current_user_id 
                          AND interaction_type = \'like\'
                    ) AS has_liked
                    -- (Nota: Faltaban los conteos de like/comment y país, 
                    -- pero los añadimos en la versión \'up\' para cumplir el requisito)
                FROM publications p
                JOIN categories c ON p.category_id = c.id
                JOIN world_cups w ON p.world_cup_id = w.id
                WHERE p.user_id = p_user_id
                  AND p.deleted_at IS NULL
                ORDER BY p.created_at DESC;
            END
        ');
    }
};
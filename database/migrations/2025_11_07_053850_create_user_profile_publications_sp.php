<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SP para OBTENER todas las publicaciones de un usuario (para su perfil)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_publications');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_publications(
                IN p_user_id INT
            )
            BEGIN
                SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.status, -- Lo necesitamos para el "badge"
                    p.published_at,
                    wc.year AS world_cup_year,
                    c.name AS category_name,
                    u.username AS author_name -- Para consistencia del componente
                FROM publications p
                JOIN users u ON p.user_id = u.id
                JOIN world_cups wc ON p.world_cup_id = wc.id
                JOIN categories c ON p.category_id = c.id
                WHERE
                    p.user_id = p_user_id
                    AND p.deleted_at IS NULL -- No mostrar las borradas por el admin
                ORDER BY p.published_at DESC;
            END
        ');

        // SP para OBTENER toda la multimedia de TODAS las publicaciones de ese usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_publications_media');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_publications_media(
                IN p_user_id INT
            )
            BEGIN
                SELECT
                    m.publication_id,
                    m.media_type,
                    m.media_data,
                    m.media_url
                FROM multimedia m
                JOIN publications p ON m.publication_id = p.id
                WHERE
                    p.user_id = p_user_id
                    AND p.deleted_at IS NULL
                    AND m.deleted_at IS NULL;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_publications');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_publications_media');
    }
};
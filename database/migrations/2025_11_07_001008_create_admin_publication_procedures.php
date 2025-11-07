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
        // SP 1: Para la lista principal del admin
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_publications');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_all_publications()
            BEGIN
                SELECT 
                    p.id,
                    p.title,
                    p.status,
                    p.published_at,
                    p.deleted_at,
                    u.username AS author_name,
                    w.year AS world_cup_year,
                    c.name AS category_name
                FROM publications p
                JOIN users u ON p.user_id = u.id
                JOIN world_cups w ON p.world_cup_id = w.id
                JOIN categories c ON p.category_id = c.id
                ORDER BY 
                    CASE WHEN p.deleted_at IS NOT NULL THEN 3 ELSE 0 END, -- Pone los borrados al final
                    CASE WHEN p.status = \'published\' THEN 0 ELSE 1 END, -- Pone los pendientes primero
                    p.published_at DESC; -- Luego los más nuevos
            END
        ');

        // SP 2: Para obtener los detalles de UNA publicación (para la página de revisión)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_publication_details');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_publication_details(IN p_id INT)
            BEGIN
                SELECT 
                    p.id,
                    p.title,
                    p.content,
                    p.status,
                    p.published_at,
                    u.username AS author_name,
                    w.year AS world_cup_year,
                    c.name AS category_name
                FROM publications p
                JOIN users u ON p.user_id = u.id
                JOIN world_cups w ON p.world_cup_id = w.id
                JOIN categories c ON p.category_id = c.id
                WHERE p.id = p_id;
            END
        ');

        // SP 3: Para obtener la multimedia de ESA publicación
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_publication_media');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_publication_media(IN p_id INT)
            BEGIN
                SELECT 
                    media_type,
                    media_data, -- El BLOB de la imagen
                    media_url   -- El enlace del video
                FROM multimedia
                WHERE publication_id = p_id
                  AND deleted_at IS NULL; -- Solo mostrar multimedia no borrada
            END
        ');

        // SP 4: Para APROBAR o RECHAZAR una publicación
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_publication_status');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_update_publication_status(
                IN p_id INT,
                IN p_status VARCHAR(10) -- Aceptará \'accepted\' o \'rejected\'
            )
            BEGIN
                UPDATE publications
                SET 
                    status = p_status,
                    -- Pone la fecha de aceptación/rechazo
                    accepted_at = (CASE WHEN p_status = \'accepted\' THEN NOW() ELSE NULL END),
                    rejected_at = (CASE WHEN p_status = \'rejected\' THEN NOW() ELSE NULL END)
                WHERE 
                    id = p_id;
            END
        ');

        // SP 5: Para BAJA LÓGICA de una publicación
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_publication');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_delete_publication(IN p_id INT)
            BEGIN
                -- 1. Dar de baja la publicación principal
                UPDATE publications SET deleted_at = NOW() WHERE id = p_id;
                
                -- 2. Dar de baja su multimedia asociada
                UPDATE multimedia SET deleted_at = NOW() WHERE publication_id = p_id;
                
                -- 3. Dar de baja sus comentarios asociados
                UPDATE comments SET deleted_at = NOW() WHERE publication_id = p_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_publications');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_publication_details');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_publication_media');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_publication_status');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_publication');
    }
};
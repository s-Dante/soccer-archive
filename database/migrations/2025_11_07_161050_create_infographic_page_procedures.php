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
        // --- SP 1: CORREGIR el SP del Mundial (para que traiga el balón) ---
        // (Este SP es de la migración: 2025_09_23_075812_create_world_cup_stored_procedures.php)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_world_cup_by_year');
        DB::unprepared('
            CREATE PROCEDURE sp_get_world_cup_by_year(IN p_year YEAR)
            BEGIN
                SELECT 
                    id, 
                    year, 
                    host_country, 
                    description, 
                    cover_image, 
                    ball_image  -- <--- CORRECCIÓN: Añadido ball_image
                FROM world_cups
                WHERE year = p_year
                AND deleted_at IS NULL;
            END
        ');

        // --- SP 2: NUEVO SP para las publicaciones de la infografía (Agrupadas) ---
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_infographic_publications');
        DB::unprepared('
            CREATE PROCEDURE sp_get_infographic_publications(IN p_world_cup_id INT)
            BEGIN
                SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.published_at,
                    u.name AS author_name,
                    c.id AS category_id,
                    c.name AS category_name
                FROM publications p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category_id = c.id
                WHERE p.world_cup_id = p_world_cup_id
                AND p.status = \'accepted\'  -- <-- ¡Solo publicaciones APROBADAS!
                AND p.deleted_at IS NULL
                AND u.deleted_at IS NULL
                ORDER BY 
                    c.name ASC, -- Agrupadas por categoría
                    p.published_at ASC; -- Y luego por fecha
            END
        ');

        // --- SP 3: NUEVO SP para la multimedia de esas publicaciones ---
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_infographic_media');
        DB::unprepared('
            CREATE PROCEDURE sp_get_infographic_media(IN p_world_cup_id INT)
            BEGIN
                SELECT 
                    m.publication_id,
                    m.media_type,
                    m.media_data,
                    m.media_url
                FROM multimedia m
                JOIN publications p ON m.publication_id = p.id
                WHERE p.world_cup_id = p_world_cup_id
                AND p.status = \'accepted\' -- Solo de publicaciones APROBADAS
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
        // Revertir el SP 1 a su versión anterior
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_world_cup_by_year');
        DB::unprepared('
            CREATE PROCEDURE sp_get_world_cup_by_year(IN p_year YEAR)
            BEGIN
                SELECT id, year, host_country, description, cover_image
                FROM world_cups
                WHERE year = p_year
                AND deleted_at IS NULL;
            END
        ');

        // Eliminar los SPs nuevos
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_infographic_publications');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_infographic_media');
    }
};
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
        // SP para obtener todos los mundiales
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_all_world_cups');
        DB::unprepared('
            CREATE PROCEDURE sp_get_all_world_cups()
            BEGIN
                SELECT id, year, host_country, description, cover_image
                FROM world_cups
                ORDER BY year ASC;
            END
        ');

        // SP para obtener un mundial por su año
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_world_cup_by_year');
        DB::unprepared('
            CREATE PROCEDURE sp_get_world_cup_by_year(IN p_year YEAR)
            BEGIN
                SELECT id, year, host_country, description, cover_image, ball_image
                FROM world_cups
                WHERE year = p_year;
            END
        ');

        // SP para obtener las publicaciones de un mundial específico
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_publications_by_world_cup');
        DB::unprepared('
            CREATE PROCEDURE sp_get_publications_by_world_cup(IN p_world_cup_id INT)
            BEGIN
                SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.published_at,
                    u.name AS author_name -- Traemos el nombre del autor
                FROM publications p
                JOIN users u ON p.user_id = u.id
                WHERE p.world_cup_id = p_world_cup_id
                AND p.status = \'accepted\' -- Solo mostramos las aceptadas
                ORDER BY p.published_at DESC;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_all_world_cups');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_world_cup_by_year');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_publications_by_world_cup');
    }
};
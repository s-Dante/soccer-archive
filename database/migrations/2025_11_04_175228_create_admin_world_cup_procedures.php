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
        // SP para CREAR un mundial
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_create_world_cup');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_create_world_cup(
                IN p_year YEAR,
                IN p_host_country VARCHAR(255),
                IN p_description TEXT,
                IN p_cover_image LONGBLOB,
                IN p_ball_image LONGBLOB
            )
            BEGIN
                INSERT INTO world_cups(year, host_country, description, cover_image, ball_image, created_at, updated_at)
                VALUES (p_year, p_host_country, p_description, p_cover_image, p_ball_image, NOW(), NOW());
            END
        ');

        // SP para LEER (GET) todos los mundiales
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_world_cups');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_all_world_cups()
            BEGIN
                SELECT id, year, host_country, deleted_at -- <-- Añadimos deleted_at
                FROM world_cups 
                -- Quitamos el "WHERE deleted_at IS NULL"
                ORDER BY year DESC;
            END
        ');

        // (Dejamos listos los SPs para Actualizar y Borrar)

        // SP para LEER (GET) un solo mundial
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_world_cup_by_id');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_world_cup_by_id(
                IN p_id INT
            )
            BEGIN
                SELECT id, year, host_country, description
                FROM world_cups 
                WHERE id = p_id
                And deleted_at IS NULL;
            END
        ');

        // SP para ACTUALIZAR (UPDATE) un mundial
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_world_cup');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_update_world_cup(
                IN p_id INT,
                IN p_year YEAR,
                IN p_host_country VARCHAR(255),
                IN p_description TEXT
                -- (Omitimos imágenes en la actualización para simplificar, 
                -- se pueden añadir SPs separados para actualizar solo la foto)
            )
            BEGIN
                UPDATE world_cups
                SET 
                    year = p_year,
                    host_country = p_host_country,
                    description = p_description,
                    updated_at = NOW()
                WHERE id = p_id;
            END
        ');

        // SP para BORRAR (BAJA LÓGICA) un mundial (Corregido: ahora borra en cascada)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_world_cup');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_delete_world_cup(
                IN p_id INT
            )
            BEGIN
                -- 1. Dar de baja el Mundial
                UPDATE world_cups
                SET deleted_at = NOW()
                WHERE id = p_id;

                -- 2. Dar de baja todas sus publicaciones asociadas
                UPDATE publications
                SET deleted_at = NOW()
                WHERE world_cup_id = p_id;
            END
        ');

        // --- SP NUEVOS PARA IMÁGENES ---
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_world_cup_cover');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_update_world_cup_cover(
                IN p_id INT,
                IN p_cover_image LONGBLOB
            )
            BEGIN
                UPDATE world_cups
                SET cover_image = p_cover_image, updated_at = NOW()
                WHERE id = p_id;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_world_cup_ball');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_update_world_cup_ball(
                IN p_id INT,
                IN p_ball_image LONGBLOB
            )
            BEGIN
                UPDATE world_cups
                SET ball_image = p_ball_image, updated_at = NOW()
                WHERE id = p_id;
            END
        ');

        // --- SP DE BORRADO (CORREGIDO A BAJA LÓGICA) ---
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_world_cup');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_delete_world_cup(
                IN p_id INT
            )
            BEGIN
                -- 1. Dar de baja el Mundial
                UPDATE world_cups
                SET deleted_at = NOW()
                WHERE id = p_id;

                -- 2. Dar de baja todas sus publicaciones asociadas
                UPDATE publications
                SET deleted_at = NOW()
                WHERE world_cup_id = p_id;
            END
        ');

        // 2. AÑADIMOS el SP para reactivar (restore)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_restore_world_cup');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_restore_world_cup(
                IN p_id INT
            )
            BEGIN
                -- 1. Reactivar el Mundial
                UPDATE world_cups
                SET deleted_at = NULL
                WHERE id = p_id;

                -- 2. Reactivar sus publicaciones asociadas
                UPDATE publications
                SET deleted_at = NULL
                WHERE world_cup_id = p_id AND deleted_at IS NOT NULL;
            END
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_create_world_cup');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_world_cups');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_world_cup_by_id');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_world_cup');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_world_cup_cover');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_update_world_cup_ball');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_world_cup');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_restore_world_cup');
    }

};
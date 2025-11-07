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
        // --- SP 1 (CORREGIDO): Ahora solo para Mundiales ---
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_world_cups_for_select');
        DB::unprepared('
            CREATE PROCEDURE sp_get_world_cups_for_select()
            BEGIN
                -- Obtenemos los mundiales activos (no borrados lógicamente)
                SELECT id, year, host_country FROM world_cups WHERE deleted_at IS NULL ORDER BY year DESC;
            END
        ');

        // --- SP 2 (NUEVO): Creado para Categorías ---
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_categories_for_select');
        DB::unprepared('
            CREATE PROCEDURE sp_get_categories_for_select()
            BEGIN
                -- Obtenemos las categorías activas (no borradas lógicamente)
                SELECT id, name FROM categories WHERE deleted_at IS NULL ORDER BY name ASC;
            END
        ');
        
        // (El SP 'sp_get_form_data_for_contribute' original ya no se usa y se elimina con el 'DROP')
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_form_data_for_contribute');


        // SP para CREAR la publicación (sin multimedia)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_create_publication');
        DB::unprepared('
            CREATE PROCEDURE sp_user_create_publication(
                IN p_title VARCHAR(255),
                IN p_content TEXT,
                IN p_user_id INT,
                IN p_category_id INT,
                IN p_world_cup_id INT
            )
            BEGIN
                INSERT INTO publications (
                    title, 
                    content, 
                    status, 
                    published_at,
                    user_id, 
                    category_id, 
                    world_cup_id,
                    created_at,
                    updated_at
                ) VALUES (
                    p_title,
                    p_content,
                    \'published\',
                    NOW(),
                    p_user_id,
                    p_category_id,
                    p_world_cup_id,
                    NOW(),
                    NOW()
                );
                
                -- Devolvemos el ID de la publicación que acabamos de crear
                SELECT LAST_INSERT_ID() AS new_publication_id;
            END
        ');

        // SP para AÑADIR multimedia (imágenes BLOB o enlaces de video)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_add_publication_media');
        DB::unprepared('
            CREATE PROCEDURE sp_user_add_publication_media(
                IN p_publication_id INT,
                IN p_media_type VARCHAR(10),
                IN p_media_data LONGBLOB,
                IN p_media_url VARCHAR(255)
            )
            BEGIN
                INSERT INTO multimedia (
                    publication_id,
                    media_type,
                    media_data,
                    media_url,
                    created_at,
                    updated_at
                ) VALUES (
                    p_publication_id,
                    p_media_type,
                    p_media_data, 
                    p_media_url, 
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_world_cups_for_select');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_categories_for_select');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_create_publication');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_add_publication_media');
    }
};
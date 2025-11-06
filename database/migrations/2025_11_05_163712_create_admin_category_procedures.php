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
        // SP para OBTENER todas las categorías
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_categories');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_all_categories()
            BEGIN
                SELECT id, name, created_at
                FROM categories
                ORDER BY name ASC;
            END
        ');

        // SP para CREAR una categoría
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_create_category');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_create_category(
                IN p_name VARCHAR(255)
            )
            BEGIN
                INSERT INTO categories (name, created_at, updated_at)
                VALUES (p_name, NOW(), NOW());
            END
        ');

        // SP para BORRAR una categoría
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_category');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_delete_category(
                IN p_id INT
            )
            BEGIN
                -- Primero eliminamos interacciones y comentarios de publicaciones asociadas
                -- (Esto es importante para evitar errores de restricción de clave externa)
                DELETE i FROM interactions i
                JOIN publications p ON i.publication_id = p.id
                WHERE p.category_id = p_id;

                DELETE c FROM comments c
                JOIN publications p ON c.publication_id = p.id
                WHERE p.category_id = p_id;

                -- Luego eliminamos las publicaciones asociadas
                DELETE FROM publications WHERE category_id = p_id;
                
                -- Finalmente, eliminamos la categoría
                DELETE FROM categories WHERE id = p_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_categories');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_create_category');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_delete_category');
    }
};

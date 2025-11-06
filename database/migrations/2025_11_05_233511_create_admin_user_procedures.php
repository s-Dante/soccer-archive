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
        // SP para OBTENER todos los usuarios (incluyendo 'deleted_at' para saber el status)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_users');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_all_users()
            BEGIN
                SELECT id, name, last_name, username, email, role, deleted_at
                FROM users
                ORDER BY name ASC;
            END
        ');

        // SP para DAR DE BAJA (Soft Delete) a un usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_soft_delete_user');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_soft_delete_user(
                IN p_id INT
            )
            BEGIN
                UPDATE users
                SET deleted_at = NOW()
                WHERE id = p_id;
                
                -- Opcional: También dar de baja sus publicaciones
                UPDATE publications
                SET deleted_at = NOW()
                WHERE user_id = p_id;
            END
        ');

        // SP para REACTIVAR (Restore) a un usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_restore_user');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_restore_user(
                IN p_id INT
            )
            BEGIN
                UPDATE users
                SET deleted_at = NULL
                WHERE id = p_id;

                -- Opcional: Reactivar también sus publicaciones
                UPDATE publications
                SET deleted_at = NULL
                WHERE user_id = p_id AND deleted_at IS NOT NULL;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_all_users');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_soft_delete_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_restore_user');
    }
};
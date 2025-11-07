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
        // SP para actualizar solo la foto de perfil
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_photo');
        DB::unprepared('
            CREATE PROCEDURE sp_update_user_photo(
                IN p_user_id INT,
                IN p_profile_photo LONGBLOB
            )
            BEGIN
                UPDATE users
                SET profile_photo = p_profile_photo, updated_at = NOW()
                WHERE id = p_user_id;
            END
        ');

        // SP para obtener la contraseña actual (para validación)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_password_by_id');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_password_by_id(
                IN p_user_id INT
            )
            BEGIN
                SELECT password FROM users WHERE id = p_user_id;
            END
        ');

        // SP para actualizar la contraseña (después de validar la antigua)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_password_by_id');
        DB::unprepared('
            CREATE PROCEDURE sp_update_user_password_by_id(
                IN p_user_id INT,
                IN p_new_password VARCHAR(255)
            )
            BEGIN
                UPDATE users
                SET password = p_new_password, updated_at = NOW()
                WHERE id = p_user_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_photo');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_password_by_id');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_password_by_id');
    }
};
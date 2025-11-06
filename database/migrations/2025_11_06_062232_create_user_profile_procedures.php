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
        // SP para OBTENER los datos del perfil para el formulario de edición
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_profile');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_profile(
                IN p_user_id INT
            )
            BEGIN
                SELECT name, last_name, username, email, gender, birthdate, country
                FROM users
                WHERE id = p_user_id;
            END
        ');

        // SP para ACTUALIZAR los datos del perfil del usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_profile');
        DB::unprepared('
            CREATE PROCEDURE sp_update_user_profile(
                IN p_user_id INT,
                IN p_name VARCHAR(255),
                IN p_last_name VARCHAR(255),
                IN p_username VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_gender ENUM("male", "female", "prefer_not_to_say"),
                IN p_birthdate DATE,
                IN p_country VARCHAR(255)
            )
            BEGIN
                UPDATE users
                SET
                    name = p_name,
                    last_name = p_last_name,
                    username = p_username,
                    email = p_email,
                    gender = p_gender,
                    birthdate = p_birthdate,
                    country = p_country,
                    updated_at = NOW()
                WHERE id = p_user_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_profile');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_profile');
    }
};
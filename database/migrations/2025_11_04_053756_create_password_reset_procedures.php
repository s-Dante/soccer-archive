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
        // SP para buscar un usuario por su email
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_find_user_by_email');
        DB::unprepared('
            CREATE PROCEDURE sp_find_user_by_email(
                IN p_email VARCHAR(255)
            )
            BEGIN
                SELECT id, name FROM users WHERE email = p_email COLLATE utf8mb4_unicode_ci LIMIT 1;
            END
        ');

        // SP para guardar el código de reseteo
        // (Usa la tabla password_reset_tokens que ya existe)
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_store_reset_token');
        DB::unprepared('
            CREATE PROCEDURE sp_store_reset_token(
                IN p_email VARCHAR(255),
                IN p_token VARCHAR(255)
            )
            BEGIN
                -- Borra tokens antiguos para este email
                DELETE FROM password_reset_tokens WHERE email = p_email COLLATE utf8mb4_unicode_ci;
                
                -- Inserta el nuevo token
                INSERT INTO password_reset_tokens (email, token, created_at) 
                VALUES (p_email, p_token, NOW());
            END
        ');

        // SP para validar el código/token
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_validate_reset_token');
        DB::unprepared('
            CREATE PROCEDURE sp_validate_reset_token(
            IN p_email VARCHAR(255),
            IN p_token VARCHAR(255)
            )
            BEGIN
                SELECT email
                FROM password_reset_tokens
                WHERE email = p_email COLLATE utf8mb4_unicode_ci -- Se eliminó el COLLATE explícito aquí
                AND token = p_token COLLATE utf8mb4_unicode_ci
                AND created_at > (NOW() - INTERVAL 10 MINUTE)
                LIMIT 1;
            END
        ');

        // SP para actualizar la contraseña y borrar el token
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_password');
        DB::unprepared('
            CREATE PROCEDURE sp_update_user_password(
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255)
            )
            BEGIN
                -- Actualiza la contraseña en la tabla de usuarios
                UPDATE users
                SET password = p_password
                WHERE email = p_email COLLATE utf8mb4_unicode_ci;

                -- Borra el token usado
                DELETE FROM password_reset_tokens
                WHERE email = p_email COLLATE utf8mb4_unicode_ci;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_reset_token');
        DB::unprepared('
            CREATE PROCEDURE sp_get_reset_token(
                IN p_email VARCHAR(255)
            )
            BEGIN
                SELECT token
                FROM password_reset_tokens
                WHERE email = p_email COLLATE utf8mb4_unicode_ci
                LIMIT 1;
            END
        ');


    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_find_user_by_email');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_store_reset_token');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_validate_reset_token');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_update_user_password');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_reset_token');
    }
};
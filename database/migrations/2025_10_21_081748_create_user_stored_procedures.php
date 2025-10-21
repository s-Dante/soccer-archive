<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_insert_user');
        DB::unprepared('
            CREATE PROCEDURE sp_insert_user(
                IN p_name VARCHAR(255),
                IN p_last_name VARCHAR(255),
                IN p_username VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_profile_photo LONGBLOB,
                IN p_gender ENUM("male", "female", "prefer_not_to_say"),
                IN p_birthdate DATE,
                IN p_country VARCHAR(255),
                IN p_role ENUM("user", "admin")
            )
            BEGIN
                INSERT INTO users(
                    name, 
                    last_name, 
                    username,
                    email, 
                    password, 
                    profile_photo, 
                    gender, 
                    birthdate, 
                    country, 
                    role,
                    created_at,
                    updated_at
                ) VALUES (
                    p_name,
                    p_last_name,
                    p_username,
                    p_email,
                    p_password,
                    p_profile_photo,
                    p_gender,
                    p_birthdate,
                    p_country,
                    p_role,
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_insert_user');
    }
};
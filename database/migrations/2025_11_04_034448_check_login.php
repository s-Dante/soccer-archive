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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_identifier');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_by_identifier(
                IN p_identifier VARCHAR(255)
            )
            BEGIN
                SELECT 
                    id, 
                    password,
                    role,
                    deleted_at
                FROM users 
                WHERE (email = p_identifier COLLATE utf8mb4_unicode_ci) OR 
                    (username = p_identifier COLLATE utf8mb4_unicode_ci)
                LIMIT 1;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_identifier');
    }
};


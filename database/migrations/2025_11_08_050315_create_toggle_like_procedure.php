<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_user_toggle_like');
        DB::unprepared('
            CREATE PROCEDURE sp_user_toggle_like(IN p_user_id INT, IN p_publication_id INT)
            BEGIN
                DECLARE v_like_exists INT;

                -- Verificar si ya existe un "like"
                SELECT COUNT(*) INTO v_like_exists
                FROM interactions
                WHERE user_id = p_user_id
                AND publication_id = p_publication_id
                AND type = "like";

                IF v_like_exists > 0 THEN
                    -- Si existe, eliminar el "like"
                    DELETE FROM interactions
                    WHERE user_id = p_user_id
                    AND publication_id = p_publication_id
                    AND type = "like";
                ELSE
                    -- Si no existe, agregar el "like"
                    INSERT INTO interactions (user_id, publication_id, type)
                    VALUES (p_user_id, p_publication_id, "like");
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_user_toggle_like');
    }
};

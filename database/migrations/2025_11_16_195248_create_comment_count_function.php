<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Función para contar comentarios (excluyendo los borrados)
        DB::unprepared('
            CREATE FUNCTION fn_get_publication_comment_count(
                p_publication_id INT
            )
            RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_comment_count INT;

                SELECT COUNT(*)
                INTO v_comment_count
                FROM comments
                WHERE publication_id = p_publication_id
                  AND deleted_at IS NULL;

                RETURN v_comment_count;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_get_publication_comment_count');
    }
};
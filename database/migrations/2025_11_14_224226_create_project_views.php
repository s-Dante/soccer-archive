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
        // Vista 1 (Total: 2) - Para la lista de usuarios en el admin
        // DB::unprepared('
        //     CREATE VIEW v_admin_users_list AS
        //     SELECT 
        //         id, 
        //         name, 
        //         email, 
        //         role, 
        //         profile_photo, 
        //         created_at, 
        //         deleted_at 
        //     FROM users
        // ');

        // Vista 2 (Total: 3) - Para la lista de categorías en el admin
        // DB::unprepared('
        //     CREATE VIEW v_admin_categories_list AS
        //     SELECT 
        //         id, 
        //         name,
        //         created_at, 
        //         deleted_at 
        //     FROM categories
        // ');

        // Vista 3 (Total: 4) - Para la lista de mundiales en el admin
        // DB::unprepared('
        //     CREATE VIEW v_admin_worldcups_list AS
        //     SELECT 
        //         id, 
        //         year, 
        //         host_country, 
        //         description, 
        //         cover_image, 
        //         ball_image, 
        //         created_at, 
        //         deleted_at 
        //     FROM world_cups
        // ');

        // Vista 4 (Total: 5) - Para la lista de publicaciones en el admin
        // DB::unprepared('
        //     CREATE VIEW v_admin_publications_list AS
        //     SELECT
        //         p.id,
        //         p.title,
        //         p.status,
        //         p.created_at,
        //         p.accepted_at,
        //         p.deleted_at,
        //         u.name AS author_name,
        //         c.name AS category_name,
        //         w.year AS world_cup_year
        //     FROM publications p
        //     JOIN users u ON p.user_id = u.id
        //     JOIN categories c ON p.category_id = c.id
        //     JOIN world_cups w ON p.world_cup_id = w.id
        // ');

        // Vista 5 (Total: 6) - Para la lista de comentarios en el admin
        // DB::unprepared('
        //     CREATE VIEW v_admin_comments_list AS
        //     SELECT
        //         c.id,
        //         c.content,
        //         c.created_at, -- La columna que faltaba
        //         c.deleted_at,
        //         u.name AS author_name,
        //         p.id AS publication_id,
        //         p.title AS publication_title
        //     FROM comments c
        //     JOIN users u ON c.user_id = u.id
        //     JOIN publications p ON c.publication_id = p.id
        // ');

        // Vista 6 (Total: 7) - Para el feed público (solo publicaciones aceptadas)
        DB::unprepared('
            CREATE VIEW v_public_publications_feed AS
            SELECT
                p.id,
                p.title,
                p.content,
                p.user_id,
                p.category_id,
                p.world_cup_id,
                p.accepted_at,
                p.created_at,
                u.name AS author_name,
                u.profile_photo AS author_avatar,
                c.name AS category_name,
                w.year AS world_cup_year,
                w.host_country,
                fn_get_publication_like_count(p.id) AS like_count 
            FROM publications p
            JOIN users u ON p.user_id = u.id
            JOIN categories c ON p.category_id = c.id
            JOIN world_cups w ON p.world_cup_id = w.id
            WHERE
                p.status = \'accepted\' AND p.deleted_at IS NULL
        ');

        // Vista 7 (Total: 8) - Para estadísticas del perfil de usuario
        DB::unprepared('
            CREATE VIEW v_user_profile_stats AS
            SELECT
                u.id AS user_id,
                u.name,
                u.email,
                u.profile_photo,
                u.created_at AS member_since,
                (SELECT COUNT(*) FROM publications WHERE user_id = u.id AND deleted_at IS NULL) AS total_publications,
                (SELECT COUNT(*) FROM comments WHERE user_id = u.id AND deleted_at IS NULL) AS total_comments,
                (SELECT SUM(fn_get_publication_like_count(id)) FROM publications WHERE user_id = u.id) AS total_likes_received
            FROM users u
            WHERE u.deleted_at IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //DB::unprepared('DROP VIEW IF EXISTS v_admin_users_list');
        //DB::unprepared('DROP VIEW IF EXISTS v_admin_categories_list');
        //DB::unprepared('DROP VIEW IF EXISTS v_admin_worldcups_list');
        //DB::unprepared('DROP VIEW IF EXISTS v_admin_publications_list');
        //DB::unprepared('DROP VIEW IF EXISTS v_admin_comments_list');
        DB::unprepared('DROP VIEW IF EXISTS v_public_publications_feed');
        DB::unprepared('DROP VIEW IF EXISTS v_user_profile_stats');
    }
};

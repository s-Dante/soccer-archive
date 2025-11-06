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
        // 1. CREAMOS LA VISTA (Requisito de BDMM)
        // Nota: Asumimos que 'published' es el estado "pendiente" antes de ser 'accepted'
        DB::unprepared('DROP VIEW IF EXISTS view_dashboard_stats');
        DB::unprepared('
            CREATE VIEW view_dashboard_stats AS
            SELECT
                (SELECT COUNT(*) FROM publications WHERE status = \'published\') AS pending_publications,
                (SELECT COUNT(*) FROM users) AS total_users,
                (SELECT COUNT(*) FROM world_cups) AS total_world_cups
        ');

        // 2. CREAMOS EL SP que lee la vista (Requisito de BDMM de "Solo SPs")
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_dashboard_stats');
        DB::unprepared('
            CREATE PROCEDURE sp_admin_get_dashboard_stats()
            BEGIN
                SELECT * FROM view_dashboard_stats;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_admin_get_dashboard_stats');
        DB::unprepared('DROP VIEW IF EXISTS view_dashboard_stats');
    }
};

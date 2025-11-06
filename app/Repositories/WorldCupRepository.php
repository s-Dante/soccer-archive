<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class WorldCupRepository
{
    /**
     * Obtiene todos los mundiales para la vista PÚBLICA (Home).
     */
    public function getForPublicIndex()
    {
        // Llama al SP público (que debe ser solo para mundiales "publicados")
        return DB::select('CALL sp_get_all_world_cups()');
    }

    /**
     * Obtiene todos los mundiales para la vista de ADMIN.
     */
    public function getForAdminIndex()
    {
        // Llama al SP de admin (que puede traer todo)
        return DB::select('CALL sp_admin_get_all_world_cups()');
    }

    /**
     * Obtiene un mundial específico por año (para la vista pública).
     */
    public function getByYear(int $year)
    {
        return DB::selectOne('CALL sp_get_world_cup_by_year(?)', [$year]);
    }

    /**
     * Crea un nuevo mundial (para el admin).
     */
    public function create(array $data): void
    {
        DB::statement('CALL sp_admin_create_world_cup(?, ?, ?, ?, ?)', [
            $data['year'],
            $data['host_country'],
            $data['description'],
            $data['cover_image'] ?? null,
            $data['ball_image'] ?? null
        ]);
    }

    public function getById(int $id)
    {
        return DB::selectOne('CALL sp_admin_get_world_cup_by_id(?)', [$id]);
    }

    /**
     * --- NUEVO: Actualiza un mundial (para el admin) ---
     */
    public function update(int $id, array $data): void
    {
        // El SP solo actualiza estos 3 campos
        DB::statement('CALL sp_admin_update_world_cup(?, ?, ?, ?)', [
            $id,
            $data['year'],
            $data['host_country'],
            $data['description']
        ]);
    }

    /**
     * --- NUEVO: Actualiza solo la imagen de portada ---
     */
    public function updateCoverImage(int $id, $imageData): void
    {
        DB::statement('CALL sp_admin_update_world_cup_cover(?, ?)', [$id, $imageData]);
    }

    /**
     * --- NUEVO: Actualiza solo la imagen del balón ---
     */
    public function updateBallImage(int $id, $imageData): void
    {
        DB::statement('CALL sp_admin_update_world_cup_ball(?, ?)', [$id, $imageData]);
    }

    /**
     * Borra (Baja Lógica) un mundial.
     */
    public function delete(int $id): void
    {
        DB::statement('CALL sp_admin_delete_world_cup(?)', [$id]);
    }

    public function restore(int $id): void
    {
        DB::statement('CALL sp_admin_restore_world_cup(?)', [$id]);
    }
}

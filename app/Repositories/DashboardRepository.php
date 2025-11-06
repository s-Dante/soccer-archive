<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    /**
     * Obtiene las estadísticas del admin dashboard.
     */
    public function getStats()
    {
        // Llama al SP y devuelve la única fila de resultados
        return DB::selectOne('CALL sp_admin_get_dashboard_stats()');
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    /**
     * Obtiene todas las categorías.
     */
    public function getAll()
    {
        return DB::select('CALL sp_admin_get_all_categories()');
    }

    /**
     * Crea una nueva categoría.
     */
    public function create(array $data): void
    {
        DB::statement('CALL sp_admin_create_category(?)', [
            $data['name']
        ]);
    }

    /**
     * Borra una categoría por su ID.
     */
    public function delete(int $id): void
    {
        DB::statement('CALL sp_admin_delete_category(?)', [$id]);
    }

    public function getWorldCupByYear() {
        
    }
    // Nota: Omitimos 'update' por ahora para simplificar el CRUD inicial
    // pero se agregaría aquí: public function update(int $id, array $data) ...
}

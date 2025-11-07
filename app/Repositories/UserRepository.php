<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Busca un usuario por email o username para el login.
     * (Incluye 'deleted_at' para la validación de login)
     */
    public function findByIdentifier(string $identifier)
    {
        $results = DB::select('CALL sp_get_user_by_identifier(?)', [$identifier]);
        return $results[0] ?? null;
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     */
    public function create(array $data): void
    {
        DB::statement(
            'CALL sp_insert_user(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['name'],
                $data['last_name'],
                $data['username'],
                $data['email'],
                Hash::make($data['password']), // Hasheamos aquí
                $data['profile_photo'] ?? null,
                $data['gender'],
                $data['birthdate'],
                $data['country'],
                'user'
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE RECUPERACIÓN DE CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    public function findByEmail(string $email)
    {
        $results = DB::select('CALL sp_find_user_by_email(?)', [$email]);
        return $results[0] ?? null;
    }

    public function storeResetToken(string $email, string $tokenHash): void
    {
        DB::statement('CALL sp_store_reset_token(?, ?)', [$email, $tokenHash]);
    }

    public function getResetToken(string $email)
    {
        $results = DB::select('CALL sp_get_reset_token(?)', [$email]);
        return $results[0] ?? null;
    }

    public function updatePassword(string $email, string $newPasswordHash): void
    {
        DB::statement('CALL sp_update_user_password(?, ?)', [$email, $newPasswordHash]);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE ADMIN (Gestión de Usuarios)
    |--------------------------------------------------------------------------
    */

    public function getAllForAdmin()
    {
        return DB::select('CALL sp_admin_get_all_users()');
    }

    public function softDelete(int $id): void
    {
        DB::statement('CALL sp_admin_soft_delete_user(?)', [$id]);
    }

    public function restore(int $id): void
    {
        DB::statement('CALL sp_admin_restore_user(?)', [$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE PERFIL DE USUARIO (NUEVOS)
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene los datos del perfil de un usuario para el formulario de edición.
     */
    public function getUserProfile(int $id)
    {
        // Usamos selectOne porque solo esperamos un resultado
        return DB::selectOne('CALL sp_get_user_profile(?)', [$id]);
    }

    /**
     * Actualiza los datos del perfil de un usuario.
     */
    public function updateUserProfile(int $id, array $data): void
    {
        DB::statement('CALL sp_update_user_profile(?, ?, ?, ?, ?, ?, ?, ?)', [
            $id,
            $data['name'],
            $data['last_name'],
            $data['username'],
            $data['email'],
            $data['gender'],
            $data['birthdate'],
            $data['country']
        ]);
    }

    public function updateProfilePhoto(int $id, $photoData): void
    {
        DB::statement('CALL sp_update_user_photo(?, ?)', [
            $id,
            $photoData
        ]);
    }

    /**
     * Actualiza la contraseña de un usuario (usando su ID).
     */
    public function updatePasswordById(int $id, string $newPasswordHash): void
    {
        DB::statement('CALL sp_update_user_password_by_id(?, ?)', [
            $id,
            $newPasswordHash
        ]);
    }
}
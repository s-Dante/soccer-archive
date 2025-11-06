<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Muestra la lista de todos los usuarios (activos e inactivos).
     */
    public function index()
    {
        $users = $this->repository->getAllForAdmin();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Da de baja (Soft Delete) a un usuario.
     */
    public function destroy(int $id)
    {
        // No permitimos que el admin se borre a sí mismo
        if ($id == auth()->id()) {
            return redirect()->route('admin.users.index')
                             ->withErrors('No puedes darte de baja a ti mismo.');
        }

        $this->repository->softDelete($id);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario dado de baja exitosamente.');
    }

    /**
     * Reactiva a un usuario.
     */
    public function restore(int $id)
    {
        $this->repository->restore($id);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario reactivado exitosamente.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante para obtener el usuario

class UserController extends Controller
{
    /**
     * Muestra la página de perfil del usuario autenticado.
     */
    public function profile()
    {
        // La vista 'user.me' ya tiene acceso a Auth::user()
        // pero aquí podríamos pasarle datos extra, como sus publicaciones.
        return view('user.me');
    }

    /**
     * Muestra la página para editar el perfil.
     */
    public function settings()
    {
        // La vista 'user.settings' también tiene acceso a Auth::user()
        // para rellenar el formulario.
        return view('user.settings');
    }

    /**
     * Muestra la página para crear una contribución.
     */
    public function contribute()
    {
        // Aquí después cargaremos los mundiales y categorías para los <select>
        return view('user.contribute');
    }

    // (Más adelante, aquí pondremos el método 'updateSettings' 
    // para guardar los cambios del formulario)
}
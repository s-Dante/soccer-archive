<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante para obtener el usuario
use App\Repositories\UserRepository;
use App\Services\CountryService;
use App\Http\Requests\User\UpdateProfileRequest;

class UserController extends Controller
{
    // --- INICIO DE LA CORRECCIÓN (Añadir constructor) ---
    protected $repository;
    protected $countryService;

    // Inyección de dependencias
    public function __construct(UserRepository $repository, CountryService $countryService)
    {
        $this->repository = $repository;
        $this->countryService = $countryService;
    }


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
        $userId = Auth::id();
        
        // 1. Obtenemos los datos del perfil desde el SP
        $user = $this->repository->getUserProfile($userId);
        
        if (!$user) {
            abort(404, 'Usuario no encontrado.');
        }

        // 2. Obtenemos la lista de países para el <select>
        $countries = $this->countryService->getCountryList();
        
        // 3. Pasamos ambas variables a la vista
        return view('user.settings', compact('user', 'countries'));
    }

    /**
     * Muestra la página para crear una contribución.
     */
    public function contribute()
    {
        // Aquí después cargaremos los mundiales y categorías para los <select>
        return view('user.contribute');
    }

    /**
     * Actualiza la información del perfil del usuario.
     */
    public function updateSettings(UpdateProfileRequest $request)
    {
        $userId = Auth::id();
        
        // 1. Los datos ya vienen validados gracias a UpdateProfileRequest
        $data = $request->validated(); 

        // 2. Llamamos al repositorio para actualizar la BD
        $this->repository->updateUserProfile($userId, $data);

        // 3. Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('user.settings')
                         ->with('success', 'Tu perfil ha sido actualizado exitosamente.');
    }

    // (Más adelante, aquí pondremos el método 'updateSettings' 
    // para guardar los cambios del formulario)
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Importante para obtener el usuario

use App\Repositories\UserRepository;
use App\Repositories\User\StorePublicationRequest;

use App\Services\CountryService;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdateProfilePhotoRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\SotrePublicationRequest;

use App\Repositories\PublicationRepository;

class UserController extends Controller
{
    // --- INICIO DE LA CORRECCIÓN (Añadir constructor) ---
    protected $repository;
    protected $countryService;
    protected $publicationRepository;

    // Inyección de dependencias
    public function __construct(UserRepository $repository, CountryService $countryService, PublicationRepository $publicationRepository)
    {
        $this->repository = $repository;
        $this->countryService = $countryService;
        $this->publicationRepository = $publicationRepository;
    }


    /**
     * Muestra la página de perfil del usuario autenticado.
     */
    public function profile(Request $request)
    {
        $userId = Auth::id();

        // 1. Leemos el parámetro 'sort' de la URL.
        // Si no existe, usamos 'date_desc' (cronológico) por defecto.
        $sort = $request->get('sort', 'date_desc');

        // 2. Pasamos el $sort al repositorio
        $profileData = $this->publicationRepository->getPublicationsForProfile($userId, $sort);

        // 3. Pasamos las variables a la vista (incluyendo $sort para el dropdown)
        return view('user.me', [
            'publications' => $profileData['publications'],
            'media' => $profileData['media'],
            'sort' => $sort // <-- Pasa la variable de sort a la vista
        ]);
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

    // --- 3. MÉTODO NUEVO PARA ACTUALIZAR LA FOTO ---
    public function updatePhoto(UpdateProfilePhotoRequest $request)
    {
        $userId = Auth::id();
        
        // 1. Obtenemos el archivo validado
        $photo = $request->file('profile_photo');
        
        // 2. Convertimos el archivo a binario
        $photoData = file_get_contents($photo->getRealPath());

        // 3. Llamamos al repositorio para actualizar la foto
        $this->repository->updateProfilePhoto($userId, $photoData);

        // 4. Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('user.settings')
                         ->with('success_photo', 'Tu foto de perfil ha sido actualizada.');
    }

    // --- 4. MÉTODO NUEVO PARA ACTUALIZAR LA CONTRASEÑA ---
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $userId = Auth::id();

        // 1. La validación (incluyendo si la 'current_password' es correcta)
        // ya se hizo en el UpdatePasswordRequest.
        
        // 2. Obtenemos la nueva contraseña ya validada
        $newPassword = $request->validated('password');

        // 3. Hasheamos la nueva contraseña
        $newPasswordHash = Hash::make($newPassword);

        // 4. Llamamos al repositorio para guardarla
        $this->repository->updatePasswordById($userId, $newPasswordHash);

        // 5. Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('user.settings')
                         ->with('success_password', 'Tu contraseña ha sido cambiada exitosamente.');
    }


    /**
     * Muestra la página de publicaciones que le han gustado al usuario.
     */
    public function showLiked()
    {
        // Verificamos si la funcionalidad está activada en el config
        if (!config('services.features.liked_posts_page', false)) {
            abort(404); // Si está en 'false', la página no existe
        }
        
        $userId = Auth::id();
        $data = $this->publicationRepository->getLikedPublications($userId);

        return view('user.liked', [
            'publications' => $data['publications'],
            'media' => $data['media']
        ]);
    }

}
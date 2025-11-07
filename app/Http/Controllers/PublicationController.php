<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PublicationRepository;
use App\Http\Requests\User\StorePublicationRequest;

class PublicationController extends Controller
{
    protected $publicationRepository;

    /**
     * Inyectamos el repositorio de publicaciones.
     */
    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

    /**
     * Muestra la página para crear una contribución.
     * (Este era UserController@contribute)
     */
    public function contribute()
    {
        // 1. Obtenemos los datos (Mundiales y Categorías) del repo
        $formData = $this->publicationRepository->getFormData();
        
        // 2. Pasamos los datos a la vista
        return view('user.contribute', [
            'worldCups' => $formData['worldCups'],
            'categories' => $formData['categories']
        ]);
    }

    /**
     * Guarda la nueva contribución (publicación).
     * (Este era UserController@storeContribution)
     */
    public function storeContribution(StorePublicationRequest $request)
    {
        // 1. Los datos ya vienen validados por StorePublicationRequest
        $data = $request->validated();
        $userId = Auth::id();

        // 2. Llamamos al repositorio para crear la publicación (esto maneja la transacción)
        $success = $this->publicationRepository->createPublication($data, $userId);

        // 3. Redirigimos con el mensaje apropiado
        if ($success) {
            // Redirigimos al perfil del usuario
            return redirect()->route('user.me') 
                             ->with('success', '¡Publicación enviada! Está pendiente de aprobación por un administrador.');
        } else {
            // Si la transacción falla, redirigimos de vuelta con un error
            return redirect()->back()
                             ->withErrors(['submit' => 'Error al guardar la publicación. Inténtalo de nuevo.'])
                             ->withInput(); // withInput() para que no pierda los datos del formulario
        }
    }
    
    // Aquí irán los métodos para mostrar publicaciones,
    // editar, borrar, etc.
}
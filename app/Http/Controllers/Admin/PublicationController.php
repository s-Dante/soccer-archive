<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\PublicationRepository; // Importamos el repositorio
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Para validar 'accepted'/'rejected'

class PublicationController extends Controller
{
    protected $repository;

    /**
     * Inyectamos el repositorio de publicaciones
     */
    public function __construct(PublicationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Muestra la lista de TODAS las publicaciones (pendientes, aprobadas, etc.)
     * Llama a: sp_admin_get_all_publications
     */
    public function index()
    {
        $publications = $this->repository->getForAdminIndex();
        
        return view('admin.publications.index', compact('publications'));
    }

    /**
     * Muestra la página de "revisión" de UNA publicación específica.
     * Llama a: sp_admin_get_publication_details y sp_admin_get_publication_media
     */
    public function show($id)
    {
        $data = $this->repository->getDetailsById($id);

        // Si el SP no devuelve detalles (ej. ID incorrecto), redirigimos
        if (empty($data['details'])) {
            return redirect()->route('admin.publications.index')
                             ->withErrors('La publicación no fue encontrada.');
        }

        return view('admin.publications.show', [
            'details' => $data['details'],
            'media' => $data['media']
        ]);
    }

    /**
     * Procesa la Aprobación o Rechazo de una publicación.
     * Llama a: sp_admin_update_publication_status
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Validamos que el estatus sea 'accepted' o 'rejected'
        $data = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'rejected'])]
        ]);

        // 2. Llamamos al repositorio
        $this->repository->updateStatus($id, $data['status']);

        $message = $data['status'] === 'accepted' ? 'Publicación Aprobada' : 'Publicación Rechazada';

        // 3. Redirigimos de vuelta a la lista
        return redirect()->route('admin.publications.index')
                         ->with('success', $message . ' exitosamente.');
    }

    /**
     * Procesa la Baja Lógica (Soft Delete) de una publicación.
     * Llama a: sp_admin_delete_publication
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.publications.index')
                         ->with('success', 'Publicación dada de baja exitosamente.');
    }
}
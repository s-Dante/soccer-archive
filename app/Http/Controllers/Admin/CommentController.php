<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CommentRepository; // Importamos el repositorio
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $repository;

    /**
     * Inyectamos el repositorio de comentarios
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Muestra la lista de TODOS los comentarios (visibles y ocultos).
     * Llama a: sp_admin_get_all_comments
     */
    public function index()
    {
        $comments = $this->repository->getAllForAdmin();
        
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Procesa la Baja Lógica (Soft Delete) de un comentario.
     * Llama a: sp_admin_delete_comment
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.comments.index')
                         ->with('success', 'Comentario dado de baja exitosamente.');
    }

    /**
     * Procesa la restauración de un comentario (Quita Soft Delete).
     * Llama a: sp_admin_restore_comment
     */
    public function restore($id)
    {
        $this->repository->restore($id);

        return redirect()->route('admin.comments.index')
                         ->with('success', 'Comentario restaurado exitosamente.');
    }
}
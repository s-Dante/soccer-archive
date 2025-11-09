<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommentRequest; // <-- El Request que acabamos de crear
use App\Models\Publication;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Almacena un nuevo comentario (o respuesta) para una publicación.
     */
    public function store(StoreCommentRequest $request, Publication $publication)
    {
        $userId = Auth::id();
        $data = $request->validated(); // Obtiene 'content' y 'parent_id' (si existe)

        try {
            // Llamamos al repositorio para crear el comentario
            $newComment = $this->commentRepository->add(
                $publication->id,
                $userId,
                $data['content'],
                $data['parent_id'] ?? null // Pasa null si 'parent_id' no está en los datos
            );

            // Devolvemos el comentario recién creado (con los datos del autor)
            // en formato JSON. Código 201 = "Created".
            return response()->json([
                'success' => true,
                'comment' => $newComment
            ], 201);

        } catch (\Exception $e) {
            // Si algo falla, devolvemos un error
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el comentario.'
            ], 500);
        }
    }
}
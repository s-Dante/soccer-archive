<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Muestra los detalles de una publicación, su autor y
     * todos sus comentarios (con sus autores) para el modal.
     */
    public function show(Publication $publication)
    {
        // Cargar las relaciones necesarias
        $publication->load([
            'user', // El autor de la publicación
            'comments' => function ($query) {
                // Cargar comentarios con sus autores
                $query->with('user')->orderBy('created_at', 'asc');
            },
            'comments.user'
        ]);

        // --- 1. Formatear la publicación (a prueba de nulos) ---
        $author = $publication->user;
        $authorName = $author?->name ?? 'Usuario Eliminado'; // <-- Usa el operador null-safe
        $authorAvatar = $author?->profile_photo
            ? 'data:image/jpeg;base64,' . base64_encode($author->profile_photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($authorName) . '&background=111827&color=fff';

        $formattedPublication = [
            'id' => $publication->id,
            'content' => $publication->content,
            'title' => $publication->title,
            'author_name' => $authorName,
            'author_avatar' => $authorAvatar,
        ];

        // --- 2. Formatear los comentarios (a prueba de nulos) ---
        $formattedComments = $publication->comments->map(function ($comment) {
            $c_author = $comment->user;
            $c_authorName = $c_author?->name ?? 'Usuario Eliminado'; // <-- Usa el operador null-safe
            $c_authorAvatar = $c_author?->profile_photo
                ? 'data:image/jpeg;base64,' . base64_encode($c_author->profile_photo)
                : 'https://ui-avatars.com/api/?name=' . urlencode($c_authorName) . '&background=4B5563&color=ffffff&rounded=true';
            
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'commented_at' => $comment->created_at->diffForHumans(),
                'parent_id' => $comment->parent_id,
                'author_name' => $c_authorName,
                'author_avatar' => $c_authorAvatar
            ];
        });

        // 3. Devolver todo como un solo objeto JSON
        return response()->json([
            'publication' => $formattedPublication,
            'comments' => $formattedComments
        ]);
    }
}
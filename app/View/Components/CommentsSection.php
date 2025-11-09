<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection; // Importante para manejar las colecciones

class CommentsSection extends Component
{
    public int $publicationId;
    public Collection $comments; // Esta será la colección jerárquizada

    /**
     * Create a new component instance.
     *
     * @param int $publicationId El ID de la publicación actual.
     * @param array $flatComments La lista "plana" de comentarios desde el repositorio.
     */
    public function __construct(int $publicationId, array $flatComments)
    {
        $this->publicationId = $publicationId;
        
        // Convertimos el array plano en una Colección de Laravel
        $commentsCollection = collect($flatComments);

        // 1. Separamos las respuestas de los comentarios principales
        $replies = $commentsCollection->whereNotNull('parent_id')->groupBy('parent_id');
        $parents = $commentsCollection->whereNull('parent_id');

        // 2. "Inyectamos" las respuestas dentro de cada comentario padre
        $this->comments = $parents->map(function ($parent) use ($replies) {
            // Creamos una nueva propiedad 'replies' en el objeto padre
            $parent->replies = $replies->get($parent->id, collect()); // Le asignamos sus respuestas
            return $parent;
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.comments-section');
    }

    /**
     * Método helper para renderizar la foto de perfil (para usar en el Blade)
     */
    public function getAvatar($comment)
    {
        if ($comment->author_photo) {
            return 'data:image/jpeg;base64,' . base64_encode($comment->author_photo);
        } else {
            // Genera un avatar genérico basado en la primera letra del nombre
            $name = urlencode($comment->author_name);
            return "https://ui-avatars.com/api/?name={$name}&background=4B5563&color=ffffff&rounded=true";
        }
    }
}
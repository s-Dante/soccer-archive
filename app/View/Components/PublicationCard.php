<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class PublicationCard extends Component
{
    public $publication;    // Contendrá los detalles (título, contenido, etc.)
    public array $images = []; // Contendrá las imágenes convertidas a Base64
    public array $videos = []; // Contendrá los enlaces de video

    /**
     * Create a new component instance.
     * Recibe los datos pasados desde el controlador.
     */
    public function __construct($details, $media)
    {
        $this->publication = $details;

        // Procesamos el array de multimedia
        foreach ($media as $item) {
            if ($item->media_type == 'image' && $item->media_data) {
                // --- ¡CONVERSIÓN DE BLOB A BASE64! ---
                // Convertimos el dato binario a una imagen que HTML puede leer.
                $this->images[] = 'data:image/jpeg;base64,' . base64_encode($item->media_data);
            } 
            elseif ($item->media_type == 'video' && $item->media_url) {
                // Convertimos un enlace de YouTube normal a un enlace "embed"
                $embedUrl = str_replace("watch?v=", "embed/", $item->media_url);
                $this->videos[] = $embedUrl;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        // Apunta al archivo de vista que crearemos a continuación
        return view('components.publication-card');
    }
}
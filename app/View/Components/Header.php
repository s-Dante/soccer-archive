<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth; // <-- Importante

class Header extends Component
{
    public $profilePhotoUrl; // <-- Hacemos pública la URL de la foto

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->profile_photo) {
                // Convertimos el BLOB a Base64 para el tag <img>
                $this->profilePhotoUrl = 'data:image/jpeg;base64,' . base64_encode($user->profile_photo);
            } else {
                // Si no tiene foto, usamos un SVG genérico como placeholder
                $this->profilePhotoUrl = $this->getDefaultAvatar();
            }
        } else {
            $this->profilePhotoUrl = null;
        }
    }

    /**
     * Proporciona un avatar SVG por defecto.
     */
    private function getDefaultAvatar(): string
    {
        $svg = '<svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
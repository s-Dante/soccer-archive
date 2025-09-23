<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WorldCupCard extends Component
{
    /**
     * Crea una nueva instancia del componente.
     */
    public function __construct(
        public object $worldCup // Aquí le decimos que recibirá un objeto con los datos del mundial
    ) {}

    /**
     * Obtiene la vista que representa el componente.
     */
    public function render(): View|Closure|string
    {
        return view('components.world-cup-card');
    }
}
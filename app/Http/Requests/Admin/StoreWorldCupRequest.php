<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorldCupRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // La ruta ya está protegida por el middleware 'admin',
        // así que aquí podemos simplemente retornar true.
        return true;
    }

    /**
     * Obtiene las reglas de validación que aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'required|numeric|unique:world_cups,year',
            'host_country' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB Max
            'ball_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB Max
        ];
    }
}

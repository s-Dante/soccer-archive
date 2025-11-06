<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorldCupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // El middleware de la ruta ya protege esto
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Obtenemos el ID del mundial desde el parámetro de la ruta
        $worldCupId = $this->route('worldcup');

        return [
            // Regla 'unique' especial: debe ser único,
            // pero ignorando el registro que ya tiene este ID.
            'year' => [
                'required',
                'numeric',
                Rule::unique('world_cups', 'year')->ignore($worldCupId)
            ],
            'host_country' => 'required|string|max:255',
            'description' => 'required|string',
            // No validamos imágenes, ya que el SP de update no las soporta por ahora
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB Max
            'ball_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB Max
        ];
    }
}
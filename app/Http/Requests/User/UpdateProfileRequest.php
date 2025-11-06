<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // La ruta ya está protegida por el middleware 'auth', así que es seguro.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Obtenemos el ID del usuario actual para ignorarlo en las reglas 'unique'
        $userId = Auth::id();

        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                // Debe ser único, PERO ignorando el registro del propio usuario
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Igual aquí, debe ser único ignorando al usuario actual
                Rule::unique('users')->ignore($userId),
            ],
            'gender' => 'required|in:male,female,prefer_not_to_say',
            // Mantenemos la regla de +12 años
            'birthdate' => 'required|date|before_or_equal:-12 years',
            'country' => 'required|string|max:255',
        ];
    }

    /**
     * Mensajes de error personalizados (opcional pero recomendado).
     */
    public function messages(): array
    {
        return [
            'birthdate.before_or_equal' => 'Debes tener al menos 12 años para usar el sitio.',
            'email.unique' => 'Este correo electrónico ya está en uso por otro usuario.',
            'username.unique' => 'Este nombre de usuario ya está en uso por otro usuario.',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir que cualquiera intente registrarse
    }

    public function rules(): array
    {
        // Regla para la edad mínima de 12 años
        $minAge = now()->subYears(12)->toDateString();

        // Todos los simbolos validos para contraseñas
        $allowedSymbols = '.,\-\/$&';

        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => [
                'required',
                'string',
                'email', // 1. Primero valida que sea un email (rápido)
                'max:255',
                'unique:users,email',
                // 2. Luego, aplica un regex estricto que requiere un TLD (ej. .com)
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i'
            ],
            'password' => [
                'required',
                'confirmed', // Busca un campo llamado password_confirmation
                Password::min(8)
                    ->letters()      // Requiere al menos una letra
                    ->mixedCase()    // Requiere mayúsculas y minúsculas
                    ->numbers(),     // Requiere al menos un número
                
                // --- ESTA ES LA LÍNEA CORREGIDA ---
                // La variable se concatena con puntos ('.') para formar una sola cadena
                'regex:/[' . $allowedSymbols . ']/'
            ],
            'gender' => 'required|string|in:male,female,prefer_not_to_say',
            'birthdate' => "required|date|before_or_equal:$minAge",
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
            'country' => 'required|string|max:255',
        ];
    }

    // Opcional: Personalizar mensajes de error
    public function messages(): array
    {
        return [
            'birthdate.before_or_equal' => 'Debes tener al menos 12 años para registrarte.',
            'password.regex' => 'La contraseña debe contener al menos un símbolo especial (., -, /, $, &).',
            'email.regex' => 'Por favor, introduce una dirección de correo válida (ej. usuario@dominio.com).',
        ];
    }
}

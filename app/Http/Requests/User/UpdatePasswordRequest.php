<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Reglas de la rúbrica (., -, /, $, &)
        $allowedSymbols = '.,\-\/$&';

        return [
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed', // Requiere un campo 'password_confirmation'
                Password::min(8)->letters()->mixedCase()->numbers(),
                'regex:/[' . $allowedSymbols . ']/'
            ],
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'password.required' => 'Debes ingresar una nueva contraseña.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La nueva contraseña debe contener al menos un símbolo (., -, /, $, &).'
        ];
    }

    /**
     * Añade validación personalizada después de las reglas básicas.
     * Aquí verificamos si la 'current_password' es correcta.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                // Obtenemos el hash de la contraseña del usuario logueado
                $userPasswordHash = Auth::user()->password;

                // Verificamos si la contraseña actual NO coincide
                if (!Hash::check($this->input('current_password'), $userPasswordHash)) {
                    $validator->errors()->add(
                        'current_password',
                        'La contraseña actual que ingresaste es incorrecta.'
                    );
                }
            }
        ];
    }
}
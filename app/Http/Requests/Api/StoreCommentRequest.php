<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // La ruta de la API ya estará protegida por 'auth:sanctum',
        // así que podemos retornar true.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:1000', // Un comentario no puede estar vacío
            
            // 'parent_id' es opcional, pero si viene...
            'parent_id' => [
                'nullable', // ...puede ser nulo (si es un comentario principal)
                'integer',
                // ...debe existir en la tabla 'comments'
                Rule::exists('comments', 'id')->whereNull('deleted_at') 
            ],
        ];
    }
}
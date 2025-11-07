<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Importante para reglas avanzadas

class StorePublicationRequest extends FormRequest
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
        // Regex para validar enlaces de YouTube o Vimeo
        $videoRegex = '/(youtube\.com|youtu\.be|vimeo\.com)/';

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535', // Límite de un campo TEXT
            
            // Validar que el 'world_cup_id' exista en la tabla 'world_cups'
            // Y que NO esté dado de baja (deleted_at IS NULL)
            'world_cup_id' => [
                'required',
                'integer',
                Rule::exists('world_cups', 'id')->whereNull('deleted_at')
            ],
            
            // Validar que el 'category_id' exista y no esté dado de baja
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->whereNull('deleted_at')
            ],

            // --- Reglas para Multimedia (Opcional) ---

            // 'images' debe ser un array (si se envía) y no puede tener más de 5 archivos
            'images' => 'nullable|array|max:5',
            // Cada item dentro del array 'images' debe ser una imagen válida
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048' // Límite de 2MB por imagen
            ],

            // 'videos' debe ser un array (si se envía) y no puede tener más de 2 enlaces
            'videos' => 'nullable|array|max:2',
            // Cada item dentro del array 'videos' debe ser un enlace válido de YouTube/Vimeo
            'videos.*' => [
                'string',
                'url',
                'regex:' . $videoRegex
            ],
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'world_cup_id.exists' => 'El mundial seleccionado no es válido.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'images.max' => 'No puedes subir más de 5 imágenes.',
            'images.*.image' => 'Uno de los archivos no es una imagen válida.',
            'images.*.max' => 'Una de las imágenes pesa más de 2MB.',
            'videos.max' => 'No puedes añadir más de 2 enlaces de video.',
            'videos.*.url' => 'Uno de los enlaces de video no es una URL válida.',
            'videos.*.regex' => 'Solo se aceptan enlaces de YouTube o Vimeo.',
        ];
    }
}
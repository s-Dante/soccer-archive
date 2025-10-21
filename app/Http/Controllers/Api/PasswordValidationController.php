<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordValidationController extends Controller
{
    public function validatePassword(Request $request)
    {
        $password = $request->input('password', '');
        $errors = [];
        $rules = [
            'min' => strlen($password) >= 8,
            'letters' => preg_match('/[a-zA-Z]/', $password),
            'mixedCase' => preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password),
            'numbers' => preg_match('/[0-9]/', $password),
            'symbols' => preg_match('/[\W_]/', $password), // Caracteres no alfanuméricos
        ];

        if (!$rules['min']) $errors[] = 'Debe tener al menos 8 caracteres.';
        if (!$rules['letters']) $errors[] = 'Debe contener letras.';
        if (!$rules['mixedCase']) $errors[] = 'Debe contener mayúsculas y minúsculas.';
        if (!$rules['numbers']) $errors[] = 'Debe contener números.';
        if (!$rules['symbols']) $errors[] = 'Debe contener un caracter especial.';

        return response()->json([
            'valid' => empty($errors),
            'rules' => $rules,
            'errors' => $errors,
        ]);
    }
}
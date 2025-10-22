<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // GET /auth/login
    public function login() {
        return view('auth.login');
    }

    // POST /auth/login
    public function loginAuth(Request $request) {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Credenciales inválidas'])->onlyInput('email');
    }

    // GET /auth/register
    public function register() {
        return view('auth.register');
    }

    // POST /auth/register
    public function registerAuth(RegisterUserRequest $request)
    {
        // El código aquí solo se ejecuta si la validación en RegisterUserRequest pasa.

        // 1. Manejar la subida de la foto de perfil (si existe)
        $profilePhotoData = null;
        if ($request->hasFile('profile_photo')) {
            // Convertimos la imagen a datos binarios para el campo BLOB
            $profilePhotoData = file_get_contents($request->file('profile_photo')->getRealPath());
        }

        // 2. Llamar al Procedimiento Almacenado para crear el usuario
        DB::statement(
            'CALL sp_insert_user(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->name,
                $request->last_name,
                $request->username,
                $request->email,
                Hash::make($request->password), // ¡Importante! Siempre hashear la contraseña
                $profilePhotoData,
                $request->gender,
                $request->birthdate,
                $request->country,
                'user' // Rol por defecto
            ]
        );

        // 3. Redirigir a la vista de login con un mensaje de éxito
        return redirect()->route('auth.login')->with('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
    }

    public function forgotPswd() {
        return view('auth.forgot-pswd');
    }

    public function forgotPswdAuth(Request $request) {
        // Envía email de reset...
        return back()->with('status', 'Te enviamos un correo con instrucciones.');
    }

    public function pswdReset($token) {
        return view('auth.pswd-reset', compact('token'));
    }

    // POST /auth/logout
    public function logout(Request $request)
    {
        // 1. Cierra la sesión del usuario actual
        Auth::logout();

        // 2. Invalida la sesión para evitar problemas de seguridad
        $request->session()->invalidate();

        // 3. Regenera el token CSRF para la siguiente sesión
        $request->session()->regenerateToken();

        // 4. Redirige al usuario a la página de inicio
        return redirect()->route('home');
    }
}

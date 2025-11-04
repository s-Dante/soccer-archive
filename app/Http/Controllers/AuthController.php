<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- AÑADIMOS EL MÉTODO PARA MOSTRAR EL LOGIN ---
    public function login()
    {
        return view('auth.login');
    }

    // --- AÑADIMOS EL MÉTODO PARA PROCESAR EL LOGIN (USANDO SP) ---
    public function loginAuth(Request $request)
    {
        // 1. Validar los datos del formulario
        $credentials = $request->validate([
            'identifier' => 'required|string', // Campo "Correo o Usuario"
            'password' => 'required|string',
        ]);

        // 2. Llamar al SP para buscar al usuario
        $user = DB::selectOne(
            'CALL sp_get_user_by_identifier(?)', 
            [$credentials['identifier']]
        );

        // 3. Verificar al usuario y la contraseña
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'identifier' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ])->onlyInput('identifier');
        }

        // 4. Iniciar sesión al usuario
        Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        // 5. Redirigir al perfil del usuario
        return redirect()->route('user.me');
    }

    // --- AÑADIMOS EL MÉTODO PARA MOSTRAR EL REGISTRO ---
    public function register()
    {
        return view('auth.register');
    }

    // POST /auth/register
    public function registerAuth(RegisterUserRequest $request)
    {
        // ... (Tu código de DB::statement para crear el usuario) ...
        DB::statement(
            'CALL sp_insert_user(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->name,
                $request->last_name,
                $request->username,
                $request->email,
                Hash::make($request->password),
                $request->hasFile('profile_photo') ? file_get_contents($request->file('profile_photo')->getRealPath()) : null,
                $request->gender,
                $request->birthdate,
                $request->country,
                'user'
            ]
        );

        // --- AÑADE ESTA LÍNEA ---
        Auth::logout(); // Cerramos cualquier sesión que se haya abierto automáticamente
        // -------------------------

        // 3. Redirigir a la vista de login con un mensaje de éxito
       return redirect()->route('auth.login')->with('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
    }

    // --- AÑADIMOS LOS MÉTODOS QUE FALTAN ---

    public function forgotPswd() {
        return view('auth.forgot-pswd');
    }

    public function forgotPswdAuth(Request $request) {
        // Lógica de envío de correo...
        return back()->with('status', 'Te enviamos un correo con instrucciones.');
    }

    public function pswdReset($token) {
        return view('auth.pswd-reset', compact('token'));
    }

    // POST /auth/logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}


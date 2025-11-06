<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // Necesario para enviar correos
use Illuminate\Support\Str; // Necesario para generar el código
use App\Mail\PasswordResetCode; // Necesario para la clase del email
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE REGISTRO
    |--------------------------------------------------------------------------
    */
    protected $repository;

    // Inyección de dependencias: Laravel nos da el repositorio automáticamente
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function register()
    {
        return view('auth.register');
    }

    public function registerAuth(RegisterUserRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = file_get_contents($request->file('profile_photo')->getRealPath());
        }
        
        // $profilePhotoData = null;
        // if ($request->hasFile('profile_photo')) {
        //     $profilePhotoData = file_get_contents($request->file('profile_photo')->getRealPath());
        // }

        $this->repository->create($data);
        // DB::statement(
        //     'CALL sp_insert_user(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        //     [
        //         $request->name,
        //         $request->last_name,
        //         $request->username,
        //         $request->email,
        //         Hash::make($request->password),
        //         $profilePhotoData,
        //         $request->gender,
        //         $request->birthdate,
        //         $request->country,
        //         'user'
        //     ]
        // );

        Auth::logout();
        return redirect()->route('auth.login')->with('success', '¡Registro exitoso! Ya puedes iniciar sesión.');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE LOGIN / LOGOUT
    |--------------------------------------------------------------------------
    */
    public function login()
    {
        return view('auth.login');
    }

    public function loginAuth(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = $this->repository->findByIdentifier($credentials['identifier']);

        //$results = DB::select('CALL sp_get_user_by_identifier(?)', [$credentials['identifier']]);
        //$user = $results[0] ?? null;

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
        }

        if ($user->deleted_at !== null) {
            // El usuario está "borrado", le mandamos un error específico.
            throw ValidationException::withMessages([
                'identifier' => 'Tu cuenta ha sido dada de baja por un administrador.',
            ]);
        }
        
        Auth::loginUsingId($user->id, $request->boolean('remember'));
        $request->session()->regenerate();
        
        // 5. REDIRIGIR SEGÚN EL ROL (¡ESTE ES EL CAMBIO!)
        if ($user->role === 'admin') {
            // Si es admin, al dashboard de admin
            return redirect()->route('admin.dashboard');
        } else {
            // Si es usuario normal, a su perfil
            return redirect()->route('user.me');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE RESETEO DE CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    // 1. Muestra el formulario para pedir el email
    public function showForgotForm()
    {
        return view('auth.forgot-pswd');
    }

    // 2. Procesa el email, genera código y lo envía
    public function sendResetLink(Request $request)
    {
        // Validación del email
        $request->validate(['email' => 'required|email']);
        
        // Buscamos al usuario con el SP
        $user = $this->repository->findByEmail($request->email);
        //$results = DB::select('CALL sp_find_user_by_email(?)', [$request->email]);
        //$user = $results[0] ?? null;

        if (!$user) {
            // No revelamos si el usuario existe o no, por seguridad
            return back()->with('success', 'Si el correo existe, hemos enviado un código.');
        }

        // Generar un código de 6 dígitos
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Guardar el código en la BD usando el SP
        // Hasheamos el código antes de guardarlo por seguridad
        $this->repository->storeResetToken($request->email, Hash::make($code));
        //DB::statement('CALL sp_store_reset_token(?, ?)', [$request->email, Hash::make($code)]);

        // Enviar el correo al usuario
        Mail::to($request->email)->send(new PasswordResetCode($code));

        // Guardamos el email en la sesión para el siguiente paso
        session(['reset_email' => $request->email]);
        
        // Redirigimos a la vista para ingresar el código
        return redirect()->route('auth.token.form')->with('success', 'Hemos enviado un código a tu correo.');
    }

    // 3. Muestra el formulario para ingresar el código
    public function showVerifyTokenForm()
    {
        // Si no hay un email en la sesión, lo mandamos de vuelta al inicio
        if (!session('reset_email')) {
            return redirect()->route('auth.forgot.form');
        }
        return view('auth.forgot-pswd-auth');
    }

    // 4. Verifica el código ingresado
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|min:6|max:6',
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('auth.forgot.form');
        }

        // Buscamos el token hasheado en la BD
        $dbToken = $this->repository->getResetToken($email);
        //$results = DB::select('SELECT token FROM password_reset_tokens WHERE email = ?', [$email]);
        //$dbToken = $results[0] ?? null;

        // Verificamos si el token de la BD existe y si coincide con el que puso el usuario
        if (!$dbToken || !Hash::check($request->token, $dbToken->token)) {
             return back()->withErrors(['token' => 'El código no es válido o ha expirado.']);
        }

        // ¡Éxito! Guardamos el token en la sesión para el paso final
        session(['reset_token_validated' => $request->token]);

        // Redirigimos al formulario final
        return redirect()->route('auth.reset.form');
    }

    // 5. Muestra el formulario para la nueva contraseña
    public function showResetPasswordForm()
    {
        // Si no tenemos un email y un token validado, no lo dejamos pasar
        if (!session('reset_email') || !session('reset_token_validated')) {
             return redirect()->route('auth.forgot.form');
        }
        
        return view('auth.pswd-reset');
    }

    // 6. Guarda la nueva contraseña
    public function resetPassword(Request $request)
    {
        $email = session('reset_email');
        $token = session('reset_token_validated');

        if (!$email || !$token) {
            return redirect()->route('auth.forgot.form');
        }

        // Validamos la nueva contraseña (usando las reglas que ya tenías)
        $allowedSymbols = '.,\-\/$&';
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers(),
                'regex:/[' . $allowedSymbols . ']/'
            ]
        ], [
            'password.regex' => 'La contraseña debe contener al menos un símbolo (., -, /, $, &).'
        ]);

        // Verificamos el token una última vez usando el SP
        $results = $this->repository->validateResetToken($email, $token);
        //$results = DB::select('CALL sp_validate_reset_token(?, ?)', [$email, $token]);
        
        // (Nota: el SP 'sp_validate_reset_token' en realidad no funciona aquí porque el token está hasheado)
        // (Mejor usamos la lógica de `verifyToken` de nuevo)

        $dbToken = $this->repository->getResetToken($email);
        //$results = DB::select('SELECT token FROM password_reset_tokens WHERE email = ?', [$email]);
        //$dbToken = $results[0] ?? null;

        if (!$dbToken || !Hash::check($token, $dbToken->token)) {
             return redirect()->route('auth.forgot.form')->withErrors(['email' => 'El token ha expirado. Intenta de nuevo.']);
        }

        // Todo bien. Actualizamos la contraseña y borramos el token con el SP
        $this->repository->updatePassword($email, Hash::make($request->password));
        // DB::statement('CALL sp_update_user_password(?, ?)', [
        //     $email,
        //     Hash::make($request->password)
        // ]);

        // Limpiamos la sesión
        session()->forget(['reset_email', 'reset_token_validated']);

        return redirect()->route('auth.login')->with('success', '¡Contraseña actualizada! Ya puedes iniciar sesión.');
    }
}


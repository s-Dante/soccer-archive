<?php

namespace App\Http\Controllers;

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
    public function registerAuth(Request $request) {
        // Valida y crea usuario...
        // Auth::login($user);
        return redirect()->route('dashboard');
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
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}

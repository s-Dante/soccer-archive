<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Rutas para la pagina de inicio
Route::get('/', fn () => view('welcome'))->name('home');
Route::post('/', fn () => view('welcome'))->name('home');

// Rutas al iniciar sesion
Route::middleware('auth')->group(function () {
    Route::view('/me', 'user.me')->name('me');
});

// Rutas de autenticacion
Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');            // muestra formulario
    Route::post('/login', 'loginAuth')->name('login.auth');  // procesa login

    Route::get('/register', 'register')->name('register');         // muestra formulario
    Route::post('/register', 'registerAuth')->name('register.auth'); // procesa registro

    Route::get('/forgot', 'forgotPswd')->name('forgot');              // muestra formulario
    Route::post('/forgot', 'forgotPswdAuth')->name('forgot.auth');    // procesa solicitud
    Route::get('/reset/{token}', 'pswdReset')->name('reset');         // muestra reset

    Route::post('/logout', 'logout')->name('logout'); // importante: POST
});


// Rutas para las paginas de utilidades
Route::get('/about', fn () => view('utils.about'))->name('about');
Route::post('/about', fn () => view('utils.about'))->name('about');
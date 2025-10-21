<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorldCupController;
use App\Http\Controllers\Api\PasswordValidationController;
use App\Http\Request\RegisterUserRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí registramos todas las rutas web para la aplicación.
| Por ahora, todas son accesibles públicamente para demostración.
|
*/

// --- RUTAS PÚBLICAS Y PRINCIPALES ---

// Página de inicio con el carrusel de mundiales
Route::get('/', [WorldCupController::class, 'index'])->name('home');

// Página de detalle de un mundial (infografía)
Route::get('/world-cup/{year}', [WorldCupController::class, 'show'])->name('worldcup.show');

// Página de resultados de búsqueda
Route::view('/search', 'search')->name('search.index');


// --- RUTAS DE AUTENTICACIÓN (Formularios) ---
Route::prefix('auth')->name('auth.')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/login/verify', 'auth.login-auth')->name('login.verify'); // Token 2FA

    Route::get('/register', fn () => view('auth.register'))->name('register.show');

    // Procesar formulario (POST)
    Route::post('/register', [RegisterUserRequest::class, 'store'])->name('register');
    
    Route::view('/forgot-password', 'auth.forgot-pswd')->name('forgot');
    Route::view('/forgot-password/verify', 'auth.forgot-pswd-auth')->name('forgot.verify'); // Token de reseteo
    Route::view('/reset-password', 'auth.pswd-reset')->name('reset');

    // Ruta de logout (simulada por ahora)
    Route::get('/logout', fn() => redirect('/'))->name('logout');
});


// --- RUTAS DEL PERFIL DE USUARIO ---
Route::prefix('user')->name('user.')->group(function () {
    Route::view('/profile', 'user.me')->name('me');
    Route::view('/settings', 'user.settings')->name('settings');
    Route::view('/contribute', 'user.contribute')->name('contribute');
});


// --- RUTAS DEL PANEL DE ADMINISTRADOR ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/publications', 'admin.publications.index')->name('publications.index');
    Route::view('/worldcups', 'admin.worldcups.index')->name('worldcups.index');
    Route::view('/worldcups/create', 'admin.worldcups.create')->name('worldcups.create');
    Route::view('/categories', 'admin.categories.index')->name('categories.index');
    Route::view('/users', 'admin.users.index')->name('users.index');
    Route::view('/comments', 'admin.comments.index')->name('comments.index');
});


// --- RUTAS PARA APIs EXTERNAS ---
Route::view('/matches', 'matches.index')->name('matches.index');
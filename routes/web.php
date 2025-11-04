<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorldCupController;
use App\Http\Controllers\Api\PasswordValidationController;
// --- IMPORTAMOS EL CONTROLADOR CORRECTO ---
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS Y PRINCIPALES ---
Route::get('/', [WorldCupController::class, 'index'])->name('home');
Route::get('/world-cup/{year}', [WorldCupController::class, 'show'])->name('worldcup.show');
Route::view('/search', 'search')->name('search.index');


// --- RUTAS DE AUTENTICACIÓN (CORREGIDAS) ---
Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    
    // Rutas de Login
    Route::get('/login', 'login')->name('login'); // Muestra el formulario
    Route::post('/login', 'loginAuth')->name('login.auth'); // Procesa el formulario

    // Rutas de Registro
    Route::get('/register', 'register')->name('register'); // Muestra el formulario
    Route::post('/register', 'registerAuth')->name('register.auth'); // Procesa el formulario

    // Rutas de Olvidé Contraseña
    Route::get('/forgot-password', 'forgotPswd')->name('forgot');
    Route::post('/forgot-password', 'forgotPswdAuth')->name('forgot.auth');
    Route::get('/reset-password/{token}', 'pswdReset')->name('reset');
    // (Aquí faltaría la ruta POST para guardar la nueva contraseña)

    // Ruta de Logout
    Route::post('/logout', 'logout')->name('logout');
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

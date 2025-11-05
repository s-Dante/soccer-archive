<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WorldCupController;
use App\Http\Controllers\AuthController; // Asegúrate de importar AuthController
use App\Http\Controllers\UserController; // Asegúrate de importar UserController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS Y PRINCIPALES ---
Route::get('/', [WorldCupController::class, 'index'])->name('home');
Route::get('/world-cup/{year}', [WorldCupController::class, 'show'])->name('worldcup.show');
Route::view('/search', 'search')->name('search.index');


// --- RUTAS DE AUTENTICACIÓN (Flujo Completo) ---
Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    
    // Rutas de Login
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginAuth')->name('login.auth');

    // Rutas de Registro
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerAuth')->name('register.auth');

    // --- Flujo de Restablecimiento de Contraseña ---
    
    // 1. Mostrar formulario para pedir email
    Route::get('/forgot-password', 'showForgotForm')->name('forgot.form');
    // 2. Procesar el email y enviar el código
    Route::post('/forgot-password', 'sendResetLink')->name('forgot.send');
    
    // 3. Mostrar formulario para ingresar el código
    Route::get('/verify-token', 'showVerifyTokenForm')->name('token.form');
    // 4. Procesar y verificar el código
    Route::post('/verify-token', 'verifyToken')->name('token.verify');

    // 5. Mostrar formulario para crear nueva contraseña
    Route::get('/reset-password', 'showResetPasswordForm')->name('reset.form');
    // 6. Guardar la nueva contraseña
    Route::post('/reset-password', 'resetPassword')->name('reset.update');

    // --- Fin del Flujo ---

    // Ruta de Logout
    Route::post('/logout', 'logout')->name('logout');
});


// --- RUTAS DEL PERFIL DE USUARIO (Protegidas) ---
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('me');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::get('/contribute', [UserController::class, 'contribute'])->name('contribute');
});

// --- RUTAS DEL PANEL DE ADMINISTRADOR (Aún públicas para demo) ---
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // --- ¡RUTAS DE MUNDIALES CORREGIDAS! ---
    // Apuntan al controlador en lugar de a la vista directamente
    Route::get('/worldcups', [WorldCupController::class, 'index'])->name('worldcups.index');
    Route::get('/worldcups/create', [WorldCupController::class, 'create'])->name('worldcups.create');
    Route::post('/worldcups', [WorldCupController::class, 'store'])->name('worldcups.store');
    // (Aquí irán las rutas de edit, update, delete)

    // Rutas que aún son estáticas (por ahora)
    Route::view('/publications', 'admin.publications.index')->name('publications.index');
    Route::view('/categories', 'admin.categories.index')->name('categories.index');
    Route::view('/users', 'admin.users.index')->name('users.index');
    Route::view('/comments', 'admin.comments.index')->name('comments.index');
});

// --- RUTAS PARA APIs EXTERNAS ---
Route::view('/matches', 'matches.index')->name('matches.index');


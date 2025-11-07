<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Asegúrate de importar AuthController
use App\Http\Controllers\UserController; // Asegúrate de importar UserController
use App\Http\Controllers\WorldCupController;
use App\Http\Controllers\PublicationController;

// Y podemos ponerle un alias al de Admin para evitar confusiones si lo necesitas abajo
use App\Http\Controllers\Admin\WorldCupController as AdminWorldCupController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PublicationController as AdminPublicationController;

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
    
    // --- RUTAS DE AJUSTES (ACTUALIZADO) ---
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    // Ruta para procesar el formulario de actualización
    Route::patch('/settings', [UserController::class, 'updateSettings'])->name('settings.update'); 

    Route::patch('/settings/photo', [UserController::class, 'updatePhoto'])->name('settings.photo'); // <-- NUEVA RUTA
    Route::patch('/settings/password', [UserController::class, 'updatePassword'])->name('settings.password'); // <-- NUEVA RUTA
    // --------------------------------------

    Route::get('/contribute', [PublicationController::class, 'contribute'])->name('contribute');
    Route::post('/contribute', [PublicationController::class, 'storeContribution'])->name('contribute.store');
    
});

// --- RUTAS DEL PANEL DE ADMINISTRADOR (Aún públicas para demo) ---
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD de Mundiales
    Route::get('/worldcups', [AdminWorldCupController::class, 'index'])->name('worldcups.index');
    Route::get('/worldcups/create', [AdminWorldCupController::class, 'create'])->name('worldcups.create');
    Route::post('/worldcups', [AdminWorldCupController::class, 'store'])->name('worldcups.store');
    Route::get('/worldcups/{worldcup}/edit', [AdminWorldCupController::class, 'edit'])->name('worldcups.edit');
    Route::put('/worldcups/{worldcup}', [AdminWorldCupController::class, 'update'])->name('worldcups.update');
    Route::delete('/worldcups/{worldcup}', [AdminWorldCupController::class, 'destroy'])->name('worldcups.destroy');
    
    // CRUD de Categorías
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // --- 2. RUTAS PARA GESTIÓN DE USUARIOS (Reemplaza el Route::view) ---
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');


    Route::patch('/worldcups/{id}/restore', [AdminWorldCupController::class, 'restore'])->name('worldcups.restore');
    // -----------------------------------------------------------------

    // Rutas estáticas restantes
    Route::get('/publications', [AdminPublicationController::class, 'index'])->name('publications.index');
    Route::get('/publications/{publication}', [AdminPublicationController::class, 'show'])->name('publications.show');
    Route::patch('/publications/{publication}', [AdminPublicationController::class, 'updateStatus'])->name('publications.updateStatus');
    Route::delete('/publications/{publication}', [AdminPublicationController::class, 'destroy'])->name('publications.destroy');

    Route::view('/comments', 'admin.comments.index')->name('comments.index');
});

// --- RUTAS PARA APIs EXTERNAS ---
Route::view('/matches', 'matches.index')->name('matches.index');


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PasswordValidationController;
use App\Http\Controllers\Api\InteractionController;
use App\Http\Controllers\Api\CountryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Ruta para obtener el usuario (ya la tenías)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- 2. ¡NUEVA RUTA PARA LIKES! ---
    // El nombre 'publication' debe coincidir con el parámetro en el controlador
    // Route::post('/publications/{publication}/like', [InteractionController::class, 'toggleLike'])
    //     ->name('api.publications.like');

    // 1. OBTENER los datos de una publicación y sus comentarios
    // Route::get('/publications/{publication}', [PublicationController::class, 'show'])
    //      ->name('api.publications.show');

    // // 2. PUBLICAR un nuevo comentario
    // Route::post('/publications/{publication}/comments', [CommentController::class, 'store'])
    //      ->name('api.comments.store');

});

//                                                                      AÑADE ESTO ↓
Route::post('/validate-password', [PasswordValidationController::class, 'validatePassword'])
            ->name('api.validate-password');

Route::get('/countries', [CountryController::class, 'index'])->name('api.countries.index');
?>
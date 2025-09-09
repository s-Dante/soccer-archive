<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', fn () => view('welcome'))->name('home');

// Autenticación
Route::get('/login', [AuthController::class, 'login'])->name('login');      // Formulario de login
Route::post('/login', [AuthController::class, 'loginAuth'])->name('login.auth'); // Procesar login

Route::get('/register', [AuthController::class, 'register'])->name('register'); // Formulario de registro
Route::post('/register', [AuthController::class, 'registerAuth'])->name('register.auth'); // Procesar registro

Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Cerrar sesión
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ¡Importante! Para saber quién está logueado
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario ha iniciado sesión Y si su rol es 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            // 2. Si es admin, lo dejamos pasar a la ruta que quería ver
            return $next($request);
        }

        // 3. Si no es admin (o no ha iniciado sesión), lo redirigimos a la página de inicio
        return redirect()->route('home');
    }
}

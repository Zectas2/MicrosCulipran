<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Manejar la petición entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $rol
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        // 1. Verificamos si el usuario no ha iniciado sesión
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Verificamos si el rol del usuario NO coincide con el rol exigido por la ruta
        if (Auth::user()->rol !== $rol) {
            // Cerramos su sesión por intento de acceso no autorizado y lo devolvemos al login
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Acceso denegado. No posee los privilegios necesarios para esta área.',
            ]);
        }

        // 3. Si todo es correcto, permitimos que la petición continúe
        return $next($request);
    }
}

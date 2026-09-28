<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacionControlador extends Controller
{
    // Mostrar la vista
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Procesar credenciales
    public function ingresar(Request $request)
    {
        // 1. Validar que los campos sean correctos y el correo tenga un formato válido
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Intentar iniciar sesión usando el motor seguro de Laravel
        if (Auth::attempt($credenciales, $request->boolean('remember'))) {

            // Regenerar la sesión para evitar ataques
            $request->session()->regenerate();

            // 3. Redirección basada en el Rol
            $rol = Auth::user()->rol;

            if ($rol === 'administrador') {
                return redirect()->intended('/admin');
            } elseif ($rol === 'conductor') {
                return redirect()->intended('/conductor');
            }

            // Por seguridad, si el rol no coincide, cerramos la sesión y bloqueamos
            Auth::logout();
            return back()->withErrors([
                'email' => 'Su cuenta no tiene permisos de acceso al sistema operativo.',
            ]);
        }

        // 4. Si las credenciales fallan, regresar con error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Destruir sesión
    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

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

    // Mostrar vista de olvido de contraseña
    public function mostrarOlvido()
    {
        return view('auth.olvido');
    }

    // Procesar solicitud de recuperación
    public function procesarOlvido(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Verificamos si el usuario existe en la base de datos
        $usuario = \App\Models\User::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'Este correo no está registrado en la flota.']);
        }

        // Aquí, en un futuro, se puede insertar un registro en una tabla de notificaciones para el Admin.
        // Por ahora, redirigimos al login con el mensaje de éxito indicando el protocolo a seguir.
        return redirect()->route('login')->with('exito', 'Solicitud enviada. Acérquese a la administración para recibir su nueva clave.');
    }
}

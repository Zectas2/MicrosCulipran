<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Carbon\Carbon;

class NotificacionControlador extends Controller
{
    public function index()
    {
        // 1. Registramos en la memoria del navegador que el usuario acaba de leer las alertas
        session(['ultima_lectura_notificaciones' => Carbon::now('America/Santiago')]);

        $notificaciones = Notificacion::orderBy('created_at', 'desc')->get();
        return view('cliente.notificaciones', compact('notificaciones'));
    }

    public function show($id)
    {
        // También actualizamos la memoria si entra directamente a una alerta específica
        session(['ultima_lectura_notificaciones' => Carbon::now('America/Santiago')]);

        $notificacion = Notificacion::findOrFail($id);
        return view('cliente.notificacion-detalle', compact('notificacion'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notificacion;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            // Buscamos la notificación más reciente
            $ultimaNotificacion = Notificacion::latest()->first();
            $tieneNotificacionesRecientes = false;

            if ($ultimaNotificacion) {
                // Recuperamos de la memoria temporal cuándo fue la última vez que este usuario abrió los mensajes
                $ultimaLectura = session('ultima_lectura_notificaciones');

                // Si nunca los ha leído, o si la última alerta es más nueva que su última lectura, encendemos la campana
                if (!$ultimaLectura || $ultimaNotificacion->created_at->greaterThan($ultimaLectura)) {
                    $tieneNotificacionesRecientes = true;
                }
            }

            $view->with('tieneNotificacionesRecientes', $tieneNotificacionesRecientes);
        });
    }
}

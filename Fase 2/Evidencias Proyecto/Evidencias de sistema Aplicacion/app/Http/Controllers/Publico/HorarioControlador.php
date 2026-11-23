<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Carbon\Carbon;

class HorarioControlador extends Controller
{
    public function index()
    {
        Carbon::setLocale('es');

        $fechaActual = Carbon::now('America/Santiago');
        $hoy = $fechaActual->toDateString();

        // 1. Filtrar por 'hora_programada' en lugar de 'hora_salida'
        // 2. Quitamos el filtro de 'finalizado' para mostrar toda la agenda del día
        $recorridos = Recorrido::whereDate('hora_programada', $hoy)
            ->orderBy('hora_programada', 'asc')
            ->get();

        $hayDatos = $recorridos->isNotEmpty();

        $horariosSalida = [];
        if ($hayDatos) {
            foreach ($recorridos as $recorrido) {
                // Formatear desde hora_programada
                $horariosSalida[] = Carbon::parse($recorrido->hora_programada)
                    ->setTimezone('America/Santiago')
                    ->format('H:i');
            }
        }

        $textoFecha = ucfirst($fechaActual->translatedFormat('l, d \d\e F'));

        return view('cliente.horarios', [
            'hayDatos'       => $hayDatos,
            'horariosSalida' => $horariosSalida,
            'fechaHoy'       => $textoFecha
        ]);
    }
}

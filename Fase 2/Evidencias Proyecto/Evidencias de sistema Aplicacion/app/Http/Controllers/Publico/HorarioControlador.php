<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Carbon\Carbon;

class HorarioControlador extends Controller
{
    public function index()
    {
        // Forzamos el idioma español para las traducciones de fechas
        Carbon::setLocale('es');

        // Establecemos la fecha de hoy en la zona horaria correcta
        $fechaActual = Carbon::now('America/Santiago');
        $hoy = $fechaActual->toDateString();

        // Extraemos los recorridos activos que corresponden estrictamente al día de hoy
        $recorridos = Recorrido::where('estado', '!=', 'finalizado')
            ->whereDate('hora_salida', $hoy)
            ->orderBy('hora_salida', 'asc')
            ->get();

        $hayDatos = $recorridos->isNotEmpty();

        // Formateamos las horas de salida
        $horariosSalida = [];
        if ($hayDatos) {
            foreach ($recorridos as $recorrido) {
                $horariosSalida[] = $recorrido->hora_salida->format('H:i');
            }
        }

        // Generamos un texto amigable con la fecha en español
        $textoFecha = ucfirst($fechaActual->translatedFormat('l, d \d\e F'));

        return view('cliente.horarios', [
            'hayDatos'       => $hayDatos,
            'horariosSalida' => $horariosSalida,
            'fechaHoy'       => $textoFecha
        ]);
    }
}

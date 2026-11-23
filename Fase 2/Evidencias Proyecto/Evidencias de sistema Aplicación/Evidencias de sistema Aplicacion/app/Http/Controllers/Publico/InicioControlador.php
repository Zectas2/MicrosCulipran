<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Carbon\Carbon;

class InicioControlador extends Controller
{
    public function index()
    {
        Carbon::setLocale('es');
        $ahora = Carbon::now('America/Santiago');
        $hoy = $ahora->toDateString();

        // 1. Buscamos estrictamente turnos 'programado' y que tengan fecha válida
        $recorridosPendientes = Recorrido::where('estado', 'programado')
            ->whereNotNull('hora_programada')
            ->orderBy('hora_programada', 'asc')
            ->get();

        $limiteTolerancia = $ahora->copy()->subMinutes(20);

        // 2. Extraer el próximo recorrido usando la hora_programada
        $proximaMicro = $recorridosPendientes->first(function ($recorrido) use ($limiteTolerancia) {
            $horaConvertida = Carbon::parse($recorrido->hora_programada)->setTimezone('America/Santiago');
            return $horaConvertida->greaterThan($limiteTolerancia);
        });

        $tiempoRestante = 'Sin programar';
        $estadoGeneral = 'Sin servicio';
        $etiquetaSuperior = 'Próxima Salida';

        if ($proximaMicro) {
            $estadoGeneral = 'Operativo';
            $horaProgramadaReal = Carbon::parse($proximaMicro->hora_programada)->setTimezone('America/Santiago');

            // Redondeo estricto
            $minutosFaltantes = (int) round($ahora->diffInMinutes($horaProgramadaReal, false));

            if ($horaProgramadaReal->isSameDay($ahora)) {
                if ($minutosFaltantes > 60) {
                    $tiempoRestante = "A las " . $horaProgramadaReal->format('H:i') . " hrs";
                } elseif ($minutosFaltantes <= 0) {
                    $tiempoRestante = 'Saliendo...';
                } else {
                    $tiempoRestante = "En {$minutosFaltantes} min";
                }
            } else {
                $etiquetaSuperior = 'Siguiente Jornada';
                if ($horaProgramadaReal->isTomorrow()) {
                    $tiempoRestante = "Mañana, " . $horaProgramadaReal->format('H:i') . " hrs";
                } else {
                    $dia = ucfirst($horaProgramadaReal->translatedFormat('l'));
                    $tiempoRestante = "{$dia}, " . $horaProgramadaReal->format('H:i') . " hrs";
                }
            }
        } else {
            // Verificar si hay micros operando AHORA
            $microsEnRuta = Recorrido::where('estado', 'en_ruta')->get()->contains(function ($r) use ($hoy) {
                if (!$r->hora_programada) return false;
                return Carbon::parse($r->hora_programada)->setTimezone('America/Santiago')->toDateString() === $hoy;
            });

            if ($microsEnRuta) {
                $estadoGeneral = 'Operativo';
                $tiempoRestante = 'Sin más salidas hoy';
            }
        }

        return view('cliente.index', compact('tiempoRestante', 'estadoGeneral', 'etiquetaSuperior'));
    }
}

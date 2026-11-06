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

        $recorridosPendientes = Recorrido::where('estado', '!=', 'finalizado')
            ->where('estado', '!=', 'en_ruta')
            ->orderBy('hora_salida', 'asc')
            ->get();

        $limiteTolerancia = $ahora->copy()->subMinutes(20);

        $proximaMicro = $recorridosPendientes->first(function ($recorrido) use ($limiteTolerancia) {
            $horaConvertida = Carbon::parse($recorrido->hora_salida)->setTimezone('America/Santiago');
            return $horaConvertida->greaterThan($limiteTolerancia);
        });

        $tiempoRestante = 'Sin programar';
        $estadoGeneral = 'Sin servicio';
        $etiquetaSuperior = 'Próxima Salida';

        if ($proximaMicro) {
            $estadoGeneral = 'Operativo';
            $horaSalidaReal = Carbon::parse($proximaMicro->hora_salida)->setTimezone('America/Santiago');

            // Aplicamos un redondeo matemático estricto para eliminar los decimales
            $minutosFaltantes = (int) round($ahora->diffInMinutes($horaSalidaReal, false));

            if ($horaSalidaReal->isSameDay($ahora)) {
                if ($minutosFaltantes > 60) {
                    $tiempoRestante = "A las " . $horaSalidaReal->format('H:i') . " hrs";
                } elseif ($minutosFaltantes <= 0) {
                    $tiempoRestante = 'Saliendo...';
                } else {
                    $tiempoRestante = "En {$minutosFaltantes} min";
                }
            } else {
                $etiquetaSuperior = 'Siguiente Jornada';
                if ($horaSalidaReal->isTomorrow()) {
                    $tiempoRestante = "Mañana, " . $horaSalidaReal->format('H:i') . " hrs";
                } else {
                    $dia = ucfirst($horaSalidaReal->translatedFormat('l'));
                    $tiempoRestante = "{$dia}, " . $horaSalidaReal->format('H:i') . " hrs";
                }
            }
        } else {
            $microsEnRuta = Recorrido::where('estado', 'en_ruta')->get()->contains(function ($r) use ($hoy) {
                return Carbon::parse($r->hora_salida)->setTimezone('America/Santiago')->toDateString() === $hoy;
            });

            if ($microsEnRuta) {
                $estadoGeneral = 'Operativo';
                $tiempoRestante = 'Sin más salidas hoy';
            }
        }

        return view('cliente.index', compact('tiempoRestante', 'estadoGeneral', 'etiquetaSuperior'));
    }
}

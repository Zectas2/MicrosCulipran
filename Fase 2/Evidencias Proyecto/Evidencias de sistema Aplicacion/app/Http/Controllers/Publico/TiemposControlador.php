<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Carbon\Carbon;

class TiemposControlador extends Controller
{
    public function index()
    {
        $hoy = Carbon::today('America/Santiago');

        // 1. Frecuencia Promedio (Calculada en base a la diferencia entre salidas programadas hoy)
        $turnosHoy = Recorrido::whereDate('hora_programada', $hoy)
            ->orderBy('hora_programada', 'asc')
            ->get();

        $frecuencia = 30; // Valor por defecto si no hay suficientes datos
        if ($turnosHoy->count() > 1) {
            $diferencias = [];
            for ($i = 1; $i < $turnosHoy->count(); $i++) {
                $diff = Carbon::parse($turnosHoy[$i]->hora_programada)
                    ->diffInMinutes(Carbon::parse($turnosHoy[$i - 1]->hora_programada));
                if ($diff > 0 && $diff <= 120) { // Ignorar saltos anormales (ej. colación)
                    $diferencias[] = $diff;
                }
            }
            if (count($diferencias) > 0) {
                $frecuencia = round(array_sum($diferencias) / count($diferencias));
            }
        }

        // 2. Duración Promedio del Viaje Completo (Basado en viajes finalizados reales los últimos 7 días)
        $viajesTerminados = Recorrido::where('estado', 'finalizado')
            ->whereNotNull('hora_salida')
            ->whereDate('updated_at', '>=', Carbon::now()->subDays(7))
            ->get();

        $duracionPromedio = 45; // Valor por defecto
        if ($viajesTerminados->count() > 0) {
            $duraciones = [];
            foreach ($viajesTerminados as $viaje) {
                $salida = Carbon::parse($viaje->hora_salida);
                $llegada = Carbon::parse($viaje->updated_at);
                $diff = $salida->diffInMinutes($llegada);

                if ($diff > 15 && $diff < 120) { // Filtro de cordura operativa
                    $duraciones[] = $diff;
                }
            }
            if (count($duraciones) > 0) {
                $duracionPromedio = round(array_sum($duraciones) / count($duraciones));
            }
        }

        // 3. Horario Operativo de Hoy
        $primerTurno = $turnosHoy->first();
        $ultimoTurno = $turnosHoy->last();
        $horaInicio = $primerTurno && $primerTurno->hora_programada ? Carbon::parse($primerTurno->hora_programada)->format('H:i') : '--:--';
        $horaFin = $ultimoTurno && $ultimoTurno->hora_programada ? Carbon::parse($ultimoTurno->hora_programada)->format('H:i') : '--:--';

        return view('cliente.tiempos', compact('frecuencia', 'duracionPromedio', 'horaInicio', 'horaFin'));
    }
}

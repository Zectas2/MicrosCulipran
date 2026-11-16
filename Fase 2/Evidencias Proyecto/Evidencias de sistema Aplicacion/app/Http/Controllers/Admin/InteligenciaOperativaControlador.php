<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recorrido;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InteligenciaOperativaControlador extends Controller
{
    public function index(Request $request)
    {
        $tz = 'America/Santiago';

        // 1. Motor de Fecha Dinámica
        // Si el usuario selecciona una fecha, la usamos. Si no, mostramos "Hoy".
        $fechaInput = $request->query('fecha');
        try {
            $fechaConsulta = $fechaInput ? Carbon::parse($fechaInput, $tz)->startOfDay() : Carbon::today($tz);
        } catch (\Exception $e) {
            $fechaConsulta = Carbon::today($tz);
        }

        $fechaValor = $fechaConsulta->format('Y-m-d'); // Para el input HTML

        // 2. Cargar recorridos finalizados en la FECHA CONSULTADA
        $recorridosFinalizados = Recorrido::with(['conductor', 'vehiculo'])
            ->where('estado', 'finalizado')
            ->whereDate('updated_at', $fechaConsulta)
            ->get();

        $vueltasCompletadas = $recorridosFinalizados->count();

        // 3. KPI: Puntualidad Real y Excepciones de Horario
        $totalSalidas = $recorridosFinalizados->count();
        $excepcionesHorario = collect();

        $salidasA_tiempo = $recorridosFinalizados->filter(function ($r) use (&$excepcionesHorario) {
            if (!$r->hora_programada || !$r->hora_salida) return false;

            $programada = Carbon::parse($r->hora_programada);
            $salidaReal = Carbon::parse($r->hora_salida);

            $minutosDiferencia = $programada->diffInMinutes($salidaReal, false);

            if ($minutosDiferencia >= -2 && $minutosDiferencia <= 8) {
                return true;
            } else {
                $estadoPuntualidad = $minutosDiferencia > 8 ? 'Atrasado' : 'Adelantado';
                $excepcionesHorario->push((object)[
                    'patente' => $r->vehiculo->patente ?? 'S/P',
                    'conductor' => $r->conductor->nombre ?? 'Desconocido',
                    'programada' => $programada->format('H:i'),
                    'salida_real' => $salidaReal->format('H:i'),
                    'diferencia_minutos' => round(abs($minutosDiferencia)),
                    'estado' => $estadoPuntualidad
                ]);
                return false;
            }
        })->count();

        $puntualidad = $totalSalidas > 0 ? round(($salidasA_tiempo / $totalSalidas) * 100) : 100;

        // 4. KPI: Velocidad Promedio Diaria
        $velocidadPromedio = DB::table('ubicacion_vehiculos')
            ->whereDate('created_at', $fechaConsulta)
            ->avg('velocidad');
        $velocidadPromedio = round($velocidadPromedio ?? 0);

        // 5. Gráfico 1: Productividad
        $productividad = $recorridosFinalizados->groupBy(function ($recorrido) {
            return $recorrido->conductor->nombre ?? 'Desconocido';
        })->map(function ($viajes) {
            return $viajes->count();
        })->sortDesc();

        $labelsProductividad = $productividad->keys()->toArray();
        $datosProductividad = $productividad->values()->toArray();

        // 6. Gráfico 2: Eficiencia
        $eficiencia = $recorridosFinalizados->groupBy(function ($recorrido) {
            return $recorrido->vehiculo->patente ?? 'Sin Patente';
        })->map(function ($viajes) {
            return $viajes->avg(function ($recorrido) {
                $salida = Carbon::parse($recorrido->hora_salida);
                $llegada = Carbon::parse($recorrido->updated_at);
                return $salida->diffInMinutes($llegada);
            });
        });

        $labelsEficiencia = $eficiencia->keys()->toArray();
        $datosEficiencia = $eficiencia->values()->map(fn($v) => round($v, 1))->toArray();

        // 7. Panel de Mantenimiento usando Modelo Vehiculo (Se mantiene general)
        $maquinasMantenimiento = Vehiculo::where('estado', 'en_mantencion')->get();

        // 8. Alertas de Exceso de Velocidad de la FECHA CONSULTADA
        $alertasVelocidad = DB::table('ubicacion_vehiculos')
            ->join('recorridos', 'ubicacion_vehiculos.recorrido_id', '=', 'recorridos.id')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->whereDate('ubicacion_vehiculos.created_at', $fechaConsulta)
            ->where('ubicacion_vehiculos.velocidad', '>', 80)
            ->select('vehiculos.patente', 'usuarios.nombre as conductor', 'ubicacion_vehiculos.velocidad', 'ubicacion_vehiculos.created_at')
            ->orderByDesc('ubicacion_vehiculos.created_at')
            ->limit(5)
            ->get();

        return view('admin.bi', compact(
            'fechaValor',
            'puntualidad',
            'vueltasCompletadas',
            'velocidadPromedio',
            'labelsProductividad',
            'datosProductividad',
            'labelsEficiencia',
            'datosEficiencia',
            'maquinasMantenimiento',
            'alertasVelocidad',
            'excepcionesHorario'
        ));
    }
}

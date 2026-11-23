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
        Carbon::setLocale('es');

        // 1. Motor de Mes Dinámico
        $mesInput = $request->query('mes');
        try {
            if ($mesInput) {
                $mesConsulta = Carbon::createFromFormat('Y-m', $mesInput, $tz)->startOfMonth();
            } else {
                $mesConsulta = Carbon::now($tz)->startOfMonth();
            }
        } catch (\Exception $e) {
            $mesConsulta = Carbon::now($tz)->startOfMonth();
        }

        $mesValor = $mesConsulta->format('Y-m'); // Para el input HTML <input type="month">
        $mesNombre = ucfirst($mesConsulta->translatedFormat('F Y')); // Ej: "Noviembre 2026"

        // 2. Cargar recorridos finalizados en el MES CONSULTADO
        $recorridosFinalizados = Recorrido::with(['conductor', 'vehiculo'])
            ->where('estado', 'finalizado')
            ->whereYear('updated_at', $mesConsulta->year)
            ->whereMonth('updated_at', $mesConsulta->month)
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
                    'programada' => $programada->format('d/m H:i'), // Incluimos día/mes
                    'salida_real' => $salidaReal->format('d/m H:i'),
                    'diferencia_minutos' => round(abs($minutosDiferencia)),
                    'estado' => $estadoPuntualidad
                ]);
                return false;
            }
        })->count();

        // CORRECCIÓN LÓGICA: Si no hay salidas (0), la puntualidad es 0%.
        $puntualidad = $totalSalidas > 0 ? round(($salidasA_tiempo / $totalSalidas) * 100) : 0;

        // 4. KPI: Velocidad Promedio Mensual
        $velocidadPromedio = DB::table('ubicacion_vehiculos')
            ->whereYear('created_at', $mesConsulta->year)
            ->whereMonth('created_at', $mesConsulta->month)
            ->avg('velocidad');
        $velocidadPromedio = round($velocidadPromedio ?? 0);

        // 5. Gráfico 1: Productividad (Mensual)
        $productividad = $recorridosFinalizados->groupBy(function ($recorrido) {
            return $recorrido->conductor->nombre ?? 'Desconocido';
        })->map(function ($viajes) {
            return $viajes->count();
        })->sortDesc();

        $labelsProductividad = $productividad->keys()->toArray();
        $datosProductividad = $productividad->values()->toArray();

        // 6. Gráfico 2: Eficiencia (Mensual)
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

        // 7. Panel de Mantenimiento usando Modelo Vehiculo
        $maquinasMantenimiento = Vehiculo::where('estado', 'en_mantencion')->get();

        // 8. Alertas de Exceso de Velocidad del MES CONSULTADO
        $alertasVelocidad = DB::table('ubicacion_vehiculos')
            ->join('recorridos', 'ubicacion_vehiculos.recorrido_id', '=', 'recorridos.id')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->whereYear('ubicacion_vehiculos.created_at', $mesConsulta->year)
            ->whereMonth('ubicacion_vehiculos.created_at', $mesConsulta->month)
            ->where('ubicacion_vehiculos.velocidad', '>', 80)
            ->select('vehiculos.patente', 'usuarios.nombre as conductor', 'ubicacion_vehiculos.velocidad', 'ubicacion_vehiculos.created_at')
            ->orderByDesc('ubicacion_vehiculos.created_at')
            ->limit(10) // Ampliado a 10 por ser vista mensual
            ->get();

        return view('admin.bi', compact(
            'mesValor',
            'mesNombre',
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

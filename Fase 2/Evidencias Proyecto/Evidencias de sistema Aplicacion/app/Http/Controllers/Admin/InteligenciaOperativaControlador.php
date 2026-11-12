<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InteligenciaOperativaControlador extends Controller
{
    public function index()
    {
        $hoy = Carbon::today('America/Santiago');

        // 1. Cargar recorridos finalizados HOY usando los Modelos Eloquent
        // Usamos whereDate en 'updated_at' porque es el momento exacto en que se finalizó la ruta
        $recorridosFinalizados = Recorrido::with(['conductor', 'vehiculo'])
            ->where('estado', 'finalizado')
            ->whereDate('updated_at', $hoy)
            ->get();

        $vueltasCompletadas = $recorridosFinalizados->count();

        // 2. KPI: Puntualidad Real y Excepciones de Horario
        $totalSalidas = $recorridosFinalizados->count();
        $excepcionesHorario = collect(); // Colección para rastrear a los infractores

        $salidasA_tiempo = $recorridosFinalizados->filter(function ($r) use (&$excepcionesHorario) {
            if (!$r->hora_programada || !$r->hora_salida) return false;

            $programada = Carbon::parse($r->hora_programada);
            $salidaReal = Carbon::parse($r->hora_salida);

            // Calculamos la diferencia (Positivo = salió tarde, Negativo = salió temprano)
            $minutosDiferencia = $programada->diffInMinutes($salidaReal, false);

            // Ventana estricta: -2 minutos (anticipación máxima) a +8 minutos (espera máxima)
            if ($minutosDiferencia >= -2 && $minutosDiferencia <= 8) {
                return true;
            } else {
                // Atrapamos al infractor para el reporte
                $estadoPuntualidad = $minutosDiferencia > 8 ? 'Atrasado' : 'Adelantado';
                $excepcionesHorario->push((object)[
                    'patente' => $r->vehiculo->patente ?? 'S/P',
                    'conductor' => $r->conductor->nombre ?? 'Desconocido',
                    'programada' => $programada->format('H:i'),
                    'salida_real' => $salidaReal->format('H:i'),
                    'diferencia_minutos' => abs($minutosDiferencia),
                    'estado' => $estadoPuntualidad
                ]);
                return false;
            }
        })->count();

        $puntualidad = $totalSalidas > 0 ? round(($salidasA_tiempo / $totalSalidas) * 100) : 100;

        // 3. KPI: Velocidad Promedio Diaria
        $velocidadPromedio = DB::table('ubicacion_vehiculos')
            ->whereDate('created_at', $hoy)
            ->avg('velocidad');
        $velocidadPromedio = round($velocidadPromedio ?? 0);

        // 4. Gráfico 1: Productividad (Matemática pura con Colecciones Eloquent)
        $productividad = $recorridosFinalizados->groupBy(function ($recorrido) {
            return $recorrido->conductor->nombre ?? 'Desconocido';
        })->map(function ($viajes) {
            return $viajes->count();
        })->sortDesc();

        $labelsProductividad = $productividad->keys()->toArray();
        $datosProductividad = $productividad->values()->toArray();

        // 5. Gráfico 2: Eficiencia (Cálculo de minutos exactos usando Carbon)
        $eficiencia = $recorridosFinalizados->groupBy(function ($recorrido) {
            return $recorrido->vehiculo->patente ?? 'Sin Patente';
        })->map(function ($viajes) {
            return $viajes->avg(function ($recorrido) {
                $salida = Carbon::parse($recorrido->hora_salida);
                $llegada = Carbon::parse($recorrido->updated_at); // El updated_at es nuestra llegada
                return $salida->diffInMinutes($llegada);
            });
        });

        $labelsEficiencia = $eficiencia->keys()->toArray();
        $datosEficiencia = $eficiencia->values()->map(fn($v) => round($v, 1))->toArray();

        // 6. Panel de Mantenimiento usando Modelo Vehiculo
        $maquinasMantenimiento = Vehiculo::where('estado', 'en_mantencion')->get();

        // 7. Alertas de Exceso de Velocidad (> 80 km/h)
        $alertasVelocidad = DB::table('ubicacion_vehiculos')
            ->join('recorridos', 'ubicacion_vehiculos.recorrido_id', '=', 'recorridos.id')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->whereDate('ubicacion_vehiculos.created_at', $hoy)
            ->where('ubicacion_vehiculos.velocidad', '>', 80)
            ->select('vehiculos.patente', 'usuarios.nombre as conductor', 'ubicacion_vehiculos.velocidad', 'ubicacion_vehiculos.created_at')
            ->orderByDesc('ubicacion_vehiculos.created_at')
            ->limit(5)
            ->get();

        return view('admin.bi', compact(
            'puntualidad',
            'vueltasCompletadas',
            'velocidadPromedio',
            'labelsProductividad',
            'datosProductividad',
            'labelsEficiencia',
            'datosEficiencia',
            'maquinasMantenimiento',
            'alertasVelocidad',
            'excepcionesHorario' // <-- Verifique que esta línea esté presente
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanelAdministracionControlador extends Controller
{
    public function index()
    {
        $hoy = \Carbon\Carbon::today('America/Santiago');

        // 1. KPI: Micros en ruta (con filtro de fecha para evitar registros colgados)
        $microsEnRuta = \Illuminate\Support\Facades\DB::table('recorridos')
            ->where('estado', 'en_ruta')
            ->whereDate('hora_salida', $hoy)
            ->count();

        // 2. KPI: Recorridos Hoy (Renombrado de $turnosHoy a $recorridosHoy para coincidir con la vista)
        $recorridosHoy = \Illuminate\Support\Facades\DB::table('recorridos')
            ->whereDate('hora_salida', $hoy)
            ->count();

        // 3. KPI: Alertas (Consultamos si hay vehículos en pana)
        $alertas = \Illuminate\Support\Facades\DB::table('vehiculos')
            ->where('estado', 'en_mantencion')
            ->count();

        // 4. Módulo de Flota Activa: Mostrar tanto en ruta como próximos a salir
        $flotaActiva = \Illuminate\Support\Facades\DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            // Ampliamos el filtro para incluir los turnos asignados que aún no parten
            ->whereIn('recorridos.estado', ['asignado', 'pendiente', 'en_ruta'])
            ->whereDate('recorridos.hora_salida', $hoy)
            ->select(
                'vehiculos.patente',
                'usuarios.nombre as conductor',
                'recorridos.hora_salida',
                'recorridos.estado'
            )
            ->orderBy('recorridos.hora_salida', 'asc')
            ->get()
            ->map(function ($recorrido) {
                $horaSalida = \Carbon\Carbon::parse($recorrido->hora_salida)->timezone('America/Santiago');
                $ahora = \Carbon\Carbon::now('America/Santiago');

                $recorrido->horaSalidaFormateada = $horaSalida->format('H:i');
                $recorrido->bgColor = 'bg-white';
                $recorrido->bordeClase = 'border-gray-200';

                // Lógica Condicional: Diferenciamos el diseño y cálculo según el estado
                if ($recorrido->estado === 'en_ruta') {
                    $recorrido->iconBg = 'bg-primario/10';
                    $recorrido->iconColor = 'text-primario';
                    $recorrido->textoEstado = 'En Ruta';
                    $recorrido->badgeClass = 'bg-green-100 text-green-800'; // Semáforo en verde

                    $recorrido->etiquetaTiempo = 'Tiempo en ruta';
                    // Aplicamos round() para truncar los decimales
                    $minutos = round($horaSalida->floatDiffInMinutes($ahora));
                    $recorrido->valorTiempo = $minutos . ' min';
                    $recorrido->textoTiempoClase = 'text-gray-700';
                } else {
                    // Estado: Asignado / Por Salir
                    $recorrido->iconBg = 'bg-gray-100';
                    $recorrido->iconColor = 'text-gray-500';
                    $recorrido->textoEstado = 'Por Salir';
                    $recorrido->badgeClass = 'bg-gray-200 text-gray-700'; // Semáforo en gris

                    $recorrido->etiquetaTiempo = 'Sale en';

                    if ($horaSalida->isPast()) {
                        // Si la hora programada ya pasó y el chofer no ha iniciado
                        $recorrido->valorTiempo = 'Atrasado';
                        $recorrido->textoTiempoClase = 'text-red-500 font-extrabold';
                        $recorrido->badgeClass = 'bg-red-100 text-red-800';
                        $recorrido->textoEstado = 'Retrasado';
                    } else {
                        // Cálculo de tiempo restante para salir, redondeado
                        $minutosRestantes = round($ahora->floatDiffInMinutes($horaSalida));
                        $recorrido->valorTiempo = $minutosRestantes . ' min';
                        $recorrido->textoTiempoClase = 'text-gray-500';
                    }
                }

                return $recorrido;
            });

        return view('admin.index', compact(
            'microsEnRuta',
            'recorridosHoy',
            'alertas',
            'flotaActiva'
        ));
    }

    public function bi()
    {
        $hoy = Carbon::today('America/Santiago');

        // 1. KPI: Vueltas Completadas
        $vueltasCompletadas = DB::table('recorridos')
            ->where('estado', 'finalizado')
            ->whereDate('updated_at', $hoy)
            ->count();

        // KPI: Puntualidad (Simulado por ahora, requiere guardar la hora teórica en la BD)
        $puntualidad = 92;

        // 2. Productividad por Chofer (Gráfico de Barras Vertical)
        $productividad = DB::table('recorridos')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->where('recorridos.estado', 'finalizado')
            ->whereDate('recorridos.updated_at', $hoy)
            ->select('usuarios.nombre', DB::raw('COUNT(recorridos.id) as total_vueltas'))
            ->groupBy('usuarios.id', 'usuarios.nombre')
            ->get();

        $labelsProductividad = $productividad->pluck('nombre');
        $datosProductividad = $productividad->pluck('total_vueltas');

        // 3. Eficiencia de Ruta - Tiempo Promedio (Gráfico Horizontal)
        $eficiencia = DB::table('recorridos')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->where('recorridos.estado', 'finalizado')
            ->whereDate('recorridos.updated_at', $hoy)
            ->select(
                'usuarios.nombre',
                DB::raw('ROUND(AVG(EXTRACT(EPOCH FROM (recorridos.updated_at::timestamp - recorridos.hora_salida::timestamp)) / 60)) as minutos_promedio')
            )
            ->groupBy('usuarios.id', 'usuarios.nombre')
            ->get();

        $labelsEficiencia = $eficiencia->pluck('nombre');
        $datosEficiencia = $eficiencia->pluck('minutos_promedio');

        // 4. Panel de Mantenimiento (Máquinas fuera de servicio)
        $maquinasMantenimiento = DB::table('vehiculos')
            ->whereIn('estado', ['en_mantencion', 'fuera_de_servicio'])
            ->select('patente', 'estado', 'updated_at')
            ->get();

        return view('admin.bi', compact(
            'vueltasCompletadas',
            'puntualidad',
            'labelsProductividad',
            'datosProductividad',
            'labelsEficiencia',
            'datosEficiencia',
            'maquinasMantenimiento'
        ));
    }
}

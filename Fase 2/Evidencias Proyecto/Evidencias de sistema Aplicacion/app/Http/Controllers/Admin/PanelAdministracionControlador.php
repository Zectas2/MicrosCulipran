<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanelAdministracionControlador extends Controller
{
    public function index()
    {
        $tz = 'America/Santiago';
        $hoy = \Carbon\Carbon::now($tz);
        $fechaActual = $hoy->format('Y-m-d');

        // 1. KPIs utilizando los Modelos Eloquent para respetar UUIDs nativamente
        $microsEnRuta = \App\Models\Recorrido::where('estado', 'en_ruta')
            ->whereDate('hora_salida', $fechaActual)
            ->count();

        $recorridosHoy = \App\Models\Recorrido::whereDate('hora_salida', $fechaActual)
            ->count();

        // Asumiendo que su modelo se llama Vehiculo
        $alertas = \App\Models\Vehiculo::where('estado', 'en_mantencion')
            ->count();

        // 2. Módulo de Flota Activa (Blindado con Eloquent y Relaciones)
        $flotaActiva = \App\Models\Recorrido::with(['vehiculo', 'conductor'])
            ->whereNotIn('estado', ['finalizado', 'cancelado'])
            ->whereDate('hora_salida', $fechaActual)
            ->orderBy('hora_salida', 'asc')
            ->get()
            ->map(function ($recorrido) use ($tz, $hoy) {
                // Gracias al $casts de su modelo, hora_salida ya es un objeto Carbon
                $horaSalida = $recorrido->hora_salida->copy()->setTimezone($tz);

                // Construimos un objeto limpio idéntico al que espera su vista Blade
                $item = new \stdClass();
                $item->patente = $recorrido->vehiculo ? $recorrido->vehiculo->patente : 'S/P';
                $item->conductor = $recorrido->conductor ? $recorrido->conductor->nombre : 'No asignado';
                $item->horaSalidaFormateada = $horaSalida->format('H:i');
                $item->bgColor = 'bg-white';
                $item->bordeClase = 'border-gray-200';

                // Lógica Condicional: Diferenciamos el diseño y cálculo según el estado
                if ($recorrido->estado === 'en_ruta') {
                    $item->iconBg = 'bg-primario/10';
                    $item->iconColor = 'text-primario';
                    $item->textoEstado = 'En Ruta';
                    $item->badgeClass = 'bg-green-100 text-green-800';

                    $item->etiquetaTiempo = 'Tiempo en ruta';
                    $minutos = round($hoy->diffInMinutes($horaSalida));
                    $item->valorTiempo = $minutos . ' min';
                    $item->textoTiempoClase = 'text-gray-700';
                } else {
                    // Estado: Asignado / Por Salir
                    $item->iconBg = 'bg-gray-100';
                    $item->iconColor = 'text-gray-500';
                    $item->textoEstado = 'Por Salir';
                    $item->badgeClass = 'bg-gray-200 text-gray-700';

                    $item->etiquetaTiempo = 'Sale en';

                    if ($hoy->greaterThan($horaSalida)) {
                        $item->valorTiempo = 'Atrasado';
                        $item->textoTiempoClase = 'text-red-500 font-extrabold';
                        $item->badgeClass = 'bg-red-100 text-red-800';
                        $item->textoEstado = 'Retrasado';
                    } else {
                        $minutosRestantes = round($horaSalida->diffInMinutes($hoy));
                        $item->valorTiempo = $minutosRestantes . ' min';
                        $item->textoTiempoClase = 'text-gray-500';
                    }
                }

                return $item;
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

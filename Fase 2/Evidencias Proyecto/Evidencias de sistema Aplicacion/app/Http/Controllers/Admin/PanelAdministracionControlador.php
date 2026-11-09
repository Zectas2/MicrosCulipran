<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanelAdministracionControlador extends Controller
{
    public function index()
    {
        $hoy = Carbon::today('America/Santiago');

        // Métricas rápidas por si la vista principal (admin.index) las requiere
        $microsEnRuta = DB::table('recorridos')
            ->where('estado', 'en_ruta')
            ->count();

        $totalVehiculos = DB::table('vehiculos')->count();
        $vehiculosOperativos = DB::table('vehiculos')
            ->where('estado', 'operativo')
            ->count();

        $choferesActivos = DB::table('usuarios')
            ->where('rol', 'conductor')
            ->where('estado', 'activo')
            ->count();

        $turnosHoy = DB::table('recorridos')
            ->whereDate('hora_salida', $hoy)
            ->count();

        return view('admin.index', compact(
            'microsEnRuta',
            'totalVehiculos',
            'vehiculosOperativos',
            'choferesActivos',
            'turnosHoy'
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

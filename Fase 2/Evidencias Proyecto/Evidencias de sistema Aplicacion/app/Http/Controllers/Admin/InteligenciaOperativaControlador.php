<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InteligenciaOperativaControlador extends Controller
{
    public function index()
    {
        $hoy = Carbon::today('America/Santiago');

        // 1. KPIs Globales
        $vueltasCompletadas = DB::table('recorridos')
            ->where('estado', 'finalizado')
            ->whereDate('hora_salida', $hoy)
            ->count();

        $puntualidad = 100; // Temporalmente fijo

        // NUEVO KPI: Velocidad Promedio Diaria de la Flota
        $velocidadPromedio = DB::table('ubicacion_vehiculos')
            ->whereDate('created_at', $hoy)
            ->avg('velocidad');
        $velocidadPromedio = round($velocidadPromedio ?? 0);

        // 2. Gráfico 1: Productividad
        $productividad = DB::table('recorridos')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->where('recorridos.estado', 'finalizado')
            ->whereDate('recorridos.hora_salida', $hoy)
            ->select('usuarios.nombre', DB::raw('count(recorridos.id) as total_vueltas'))
            ->groupBy('usuarios.id', 'usuarios.nombre')
            ->orderByDesc('total_vueltas')
            ->get();

        $labelsProductividad = $productividad->pluck('nombre')->toArray();
        $datosProductividad = $productividad->pluck('total_vueltas')->toArray();

        // 3. Gráfico 2: Eficiencia (Minutos)
        $eficiencia = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->where('recorridos.estado', 'finalizado')
            ->whereDate('recorridos.hora_salida', $hoy)
            ->select(
                'vehiculos.patente',
                DB::raw('AVG(EXTRACT(EPOCH FROM (recorridos.updated_at - recorridos.hora_salida))/60) as minutos_promedio')
            )
            ->groupBy('vehiculos.id', 'vehiculos.patente')
            ->get();

        $labelsEficiencia = $eficiencia->pluck('patente')->toArray();
        $datosEficiencia = $eficiencia->pluck('minutos_promedio')->map(function ($val) {
            return round($val, 1);
        })->toArray();

        // 4. Panel de Mantenimiento
        $maquinasMantenimiento = DB::table('vehiculos')
            ->where('estado', 'en_mantencion')
            ->select('patente', 'estado', 'updated_at')
            ->get();

        // NUEVO PANEL: Alertas de Exceso de Velocidad (> 80 km/h)
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
            'alertasVelocidad'
        ));
    }
}

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

        // 1. KPI: Vueltas Completadas Hoy
        $vueltasCompletadas = DB::table('recorridos')
            ->where('estado', 'finalizado')
            ->whereDate('hora_salida', $hoy)
            ->count();

        // 2. KPI: Puntualidad (Fijado en 100% temporalmente)
        $puntualidad = 100;

        // 3. Gráfico 1: Productividad por Chofer
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

        // 4. Gráfico 2: Eficiencia de Ruta 
        // Calculamos la diferencia en minutos entre hora_salida y updated_at (momento exacto de finalización)
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

        // 5. Panel de Control: Máquinas en Mantenimiento
        $maquinasMantenimiento = DB::table('vehiculos')
            ->where('estado', 'en_mantencion')
            ->select('patente', 'estado', 'updated_at')
            ->get();

        return view('admin.inteligencia', compact(
            'puntualidad',
            'vueltasCompletadas',
            'labelsProductividad',
            'datosProductividad',
            'labelsEficiencia',
            'datosEficiencia',
            'maquinasMantenimiento'
        ));
    }
}

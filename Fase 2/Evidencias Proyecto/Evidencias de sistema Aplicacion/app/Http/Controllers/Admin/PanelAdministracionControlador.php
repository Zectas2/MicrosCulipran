<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanelAdministracionControlador extends Controller
{
    public function index()
    {
        // 1. Configuramos la zona horaria exacta para todo el cálculo
        $tz = 'America/Santiago';
        $hoy = \Carbon\Carbon::now($tz);
        $fechaActual = $hoy->format('Y-m-d'); // Extraemos solo el string de la fecha '2026-11-09'

        // 2. KPI: Micros en ruta
        $microsEnRuta = \Illuminate\Support\Facades\DB::table('recorridos')
            ->where('estado', 'en_ruta')
            // Usamos date() en SQL crudo para evitar discrepancias de casteo en Supabase
            ->whereRaw("DATE(hora_salida) = ?", [$fechaActual])
            ->count();

        // 3. KPI: Recorridos Hoy
        $recorridosHoy = \Illuminate\Support\Facades\DB::table('recorridos')
            ->whereRaw("DATE(hora_salida) = ?", [$fechaActual])
            ->count();

        // 4. KPI: Alertas
        $alertas = \Illuminate\Support\Facades\DB::table('vehiculos')
            ->where('estado', 'en_mantencion')
            ->count();

        // 5. Módulo de Flota Activa: Mostrar en ruta y próximos a salir
        $flotaActiva = \Illuminate\Support\Facades\DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->whereIn('recorridos.estado', ['asignado', 'pendiente', 'en_ruta'])
            ->whereRaw("DATE(recorridos.hora_salida) = ?", [$fechaActual])
            ->select(
                'vehiculos.patente',
                'usuarios.nombre as conductor',
                'recorridos.hora_salida',
                'recorridos.estado'
            )
            ->orderBy('recorridos.hora_salida', 'asc')
            ->get()
            ->map(function ($recorrido) use ($tz) {
                // Parseamos la hora guardada asumiendo que está en zona horaria local
                $horaSalida = \Carbon\Carbon::parse($recorrido->hora_salida, $tz);
                $ahora = \Carbon\Carbon::now($tz);

                $recorrido->horaSalidaFormateada = $horaSalida->format('H:i');
                $recorrido->bgColor = 'bg-white';
                $recorrido->bordeClase = 'border-gray-200';

                // Lógica Condicional: Diferenciamos el diseño y cálculo según el estado
                if ($recorrido->estado === 'en_ruta') {
                    $recorrido->iconBg = 'bg-primario/10';
                    $recorrido->iconColor = 'text-primario';
                    $recorrido->textoEstado = 'En Ruta';
                    $recorrido->badgeClass = 'bg-green-100 text-green-800';

                    $recorrido->etiquetaTiempo = 'Tiempo en ruta';
                    // Cálculo absoluto de minutos en ruta
                    $minutos = round($ahora->diffInMinutes($horaSalida));
                    $recorrido->valorTiempo = $minutos . ' min';
                    $recorrido->textoTiempoClase = 'text-gray-700';
                } else {
                    // Estado: Asignado / Por Salir
                    $recorrido->iconBg = 'bg-gray-100';
                    $recorrido->iconColor = 'text-gray-500';
                    $recorrido->textoEstado = 'Por Salir';
                    $recorrido->badgeClass = 'bg-gray-200 text-gray-700';

                    $recorrido->etiquetaTiempo = 'Sale en';

                    if ($ahora->greaterThan($horaSalida)) {
                        // La hora actual es mayor a la hora de salida programada = Atrasado
                        $recorrido->valorTiempo = 'Atrasado';
                        $recorrido->textoTiempoClase = 'text-red-500 font-extrabold';
                        $recorrido->badgeClass = 'bg-red-100 text-red-800';
                        $recorrido->textoEstado = 'Retrasado';
                    } else {
                        // Cálculo de tiempo restante para salir
                        $minutosRestantes = round($horaSalida->diffInMinutes($ahora));
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

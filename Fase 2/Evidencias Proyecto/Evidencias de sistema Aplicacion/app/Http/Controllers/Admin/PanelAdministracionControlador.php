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

        // Cálculo de KPIs
        $microsEnRuta = DB::table('recorridos')->where('estado', 'en_ruta')->count();
        $recorridosHoy = DB::table('recorridos')->whereDate('hora_salida', $hoy)->count();
        $alertas = DB::table('vehiculos')->whereIn('estado', ['en_mantencion', 'fuera_de_servicio'])->count();

        // Monitoreo de Flota Activa
        $flotaActiva = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->whereDate('recorridos.hora_salida', $hoy)
            ->where('recorridos.estado', '!=', 'finalizado') // <-- Filtro de exclusión aplicado aquí
            ->select(
                'vehiculos.patente',
                'vehiculos.estado as estado_vehiculo',
                'usuarios.nombre as conductor',
                'recorridos.hora_salida',
                'recorridos.estado as estado_recorrido'
            )
            ->orderBy('recorridos.hora_salida', 'desc')
            ->get()
            ->map(function ($recorrido) {
                $isError = in_array($recorrido->estado_vehiculo, ['en_mantencion', 'fuera_de_servicio']);

                $recorrido->bgColor = $isError ? 'bg-red-50/50 hover:bg-red-50' : 'hover:bg-fondo';
                $recorrido->iconBg = $isError ? 'bg-peligro/10 border-peligro/20' : 'bg-acento/10 border-transparent';
                $recorrido->iconColor = $isError ? 'text-peligro' : 'text-acento';
                $recorrido->badgeClass = $isError ? 'bg-peligro text-secundario animate-pulse' : 'bg-acento/10 border border-acento/20 text-acento';
                $recorrido->bordeClase = $isError ? 'border-peligro/30' : 'border-gray-200';
                $recorrido->textoTiempoClase = $isError ? 'text-peligro' : 'text-primario';

                $recorrido->textoEstado = ucwords(str_replace('_', ' ', $recorrido->estado_recorrido));

                $horaParseada = Carbon::parse($recorrido->hora_salida)->timezone('America/Santiago');
                $recorrido->horaSalidaFormateada = $horaParseada->format('H:i');

                // Lógica condicional para el tiempo en ruta
                if (in_array($recorrido->estado_recorrido, ['asignado', 'programado'])) {
                    $recorrido->etiquetaTiempo = 'Estado Operativo';
                    $recorrido->valorTiempo = 'En Espera';
                    $recorrido->textoTiempoClase = 'text-gray-500';
                } else {
                    $recorrido->etiquetaTiempo = 'Tiempo en Ruta';
                    $recorrido->valorTiempo = (int) round($horaParseada->diffInMinutes(now('America/Santiago'))) . ' min';
                    $recorrido->textoTiempoClase = $isError ? 'text-peligro' : 'text-primario';
                }

                return $recorrido;
            });

        return view('admin.index', compact('microsEnRuta', 'recorridosHoy', 'alertas', 'flotaActiva'));
    }
}

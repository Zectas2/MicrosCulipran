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

        // 1. KPIs utilizando hora_programada para el conteo del día
        $microsEnRuta = \App\Models\Recorrido::where('estado', 'en_ruta')
            ->whereDate('hora_programada', $fechaActual)
            ->count();

        $recorridosHoy = \App\Models\Recorrido::whereDate('hora_programada', $fechaActual)
            ->count();

        $alertas = \App\Models\Vehiculo::where('estado', 'en_mantencion')
            ->count();

        // 2. Módulo de Flota Activa
        $flotaActiva = \App\Models\Recorrido::with(['vehiculo', 'conductor'])
            ->whereNotIn('estado', ['finalizado', 'cancelado'])
            ->whereDate('hora_programada', $fechaActual) // Filtramos por la agenda
            ->orderBy('hora_programada', 'asc') // Ordenamos por itinerario
            ->get()
            ->map(function ($recorrido) use ($tz, $hoy) {
                // La base de la vista es la hora programada
                $horaProgramada = \Carbon\Carbon::parse($recorrido->hora_programada)->setTimezone($tz);

                $item = new \stdClass();
                $item->patente = $recorrido->vehiculo ? $recorrido->vehiculo->patente : 'S/P';
                $item->conductor = $recorrido->conductor ? $recorrido->conductor->nombre : 'No asignado';
                $item->horaSalidaFormateada = $horaProgramada->format('H:i'); // Mostramos la hora teórica en la tarjeta
                $item->bgColor = 'bg-white';
                $item->bordeClase = 'border-gray-200';

                // Lógica Condicional separada
                if ($recorrido->estado === 'en_ruta') {
                    // Si ya salió, usamos la hora_salida real para medir el tiempo conduciendo
                    $horaSalidaReal = \Carbon\Carbon::parse($recorrido->hora_salida)->setTimezone($tz);

                    $item->iconBg = 'bg-primario/10';
                    $item->iconColor = 'text-primario';
                    $item->textoEstado = 'En Ruta';
                    $item->badgeClass = 'bg-green-100 text-green-800';

                    $item->etiquetaTiempo = 'Tiempo en ruta';
                    $minutos = round($hoy->diffInMinutes($horaSalidaReal));
                    $item->valorTiempo = $minutos . ' min';
                    $item->textoTiempoClase = 'text-gray-700';
                } else {
                    // Si NO ha salido (programado), calculamos cuánto falta para la hora programada
                    $item->iconBg = 'bg-gray-100';
                    $item->iconColor = 'text-gray-500';
                    $item->textoEstado = 'Por Salir';
                    $item->badgeClass = 'bg-gray-200 text-gray-700';

                    $item->etiquetaTiempo = 'Sale en';

                    if ($hoy->greaterThan($horaProgramada)) {
                        $item->valorTiempo = 'Atrasado';
                        $item->textoTiempoClase = 'text-red-500 font-extrabold';
                        $item->badgeClass = 'bg-red-100 text-red-800';
                        $item->textoEstado = 'Retrasado';
                    } else {
                        $minutosRestantes = abs(round($hoy->diffInMinutes($horaProgramada)));
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
}

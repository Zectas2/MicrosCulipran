<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MapaControlador extends Controller
{
    public function index()
    {
        // Obtenemos la última ubicación conocida de las micros que están 'en_ruta' hoy
        $microsActivas = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            // Subconsulta para obtener solo el último registro de ubicación por recorrido
            ->join('ubicacion_vehiculos', function ($join) {
                $join->on('recorridos.id', '=', 'ubicacion_vehiculos.recorrido_id')
                    ->whereRaw('ubicacion_vehiculos.id = (SELECT MAX(id) FROM ubicacion_vehiculos WHERE recorrido_id = recorridos.id)');
            })
            ->where('recorridos.estado', 'en_ruta')
            ->whereDate('recorridos.hora_salida', Carbon::today('America/Santiago'))
            ->select(
                'vehiculos.id as vehiculoId',
                'vehiculos.patente',
                'ubicacion_vehiculos.latitud as lat',
                'ubicacion_vehiculos.longitud as lng',
                'ubicacion_vehiculos.velocidad'
            )
            ->get();

        return view('cliente.mapa', compact('microsActivas'));
    }
}

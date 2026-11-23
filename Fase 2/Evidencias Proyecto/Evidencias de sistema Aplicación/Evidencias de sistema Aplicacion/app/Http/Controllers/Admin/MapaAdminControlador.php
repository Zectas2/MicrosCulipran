<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MapaAdminControlador extends Controller
{
    public function index()
    {
        $microsActivas = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->join('ubicacion_vehiculos', function ($join) {
                $join->on('recorridos.id', '=', 'ubicacion_vehiculos.recorrido_id')
                    ->whereRaw('ubicacion_vehiculos.id = (SELECT MAX(id) FROM ubicacion_vehiculos WHERE recorrido_id = recorridos.id)');
            })
            ->where('recorridos.estado', 'en_ruta')
            ->whereDate('recorridos.hora_salida', Carbon::today('America/Santiago'))
            ->select(
                'vehiculos.id as vehiculoId',
                'vehiculos.patente',
                'usuarios.nombre as conductor', // Corrección aplicada aquí
                'ubicacion_vehiculos.latitud as lat',
                'ubicacion_vehiculos.longitud as lng',
                'ubicacion_vehiculos.velocidad'
            )
            ->get();

        $cantidadActivas = $microsActivas->count();

        return view('admin.mapa', compact('cantidadActivas', 'microsActivas'));
    }

    public function datosEnVivo()
    {
        $microsActivas = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->join('ubicacion_vehiculos', function ($join) {
                $join->on('recorridos.id', '=', 'ubicacion_vehiculos.recorrido_id')
                    ->whereRaw('ubicacion_vehiculos.id = (SELECT MAX(id) FROM ubicacion_vehiculos WHERE recorrido_id = recorridos.id)');
            })
            ->where('recorridos.estado', 'en_ruta')
            ->whereDate('recorridos.hora_salida', Carbon::today('America/Santiago'))
            ->select(
                'vehiculos.id as vehiculoId',
                'vehiculos.patente',
                'usuarios.nombre as conductor',
                'ubicacion_vehiculos.latitud as lat',
                'ubicacion_vehiculos.longitud as lng',
                'ubicacion_vehiculos.velocidad'
            )
            ->get();

        return response()->json($microsActivas);
    }
}

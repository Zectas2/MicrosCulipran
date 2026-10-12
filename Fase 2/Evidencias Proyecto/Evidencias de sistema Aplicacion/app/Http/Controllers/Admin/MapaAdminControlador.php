<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Carbon\Carbon;

class MapaAdminControlador extends Controller
{
    public function index()
    {
        // 1. Extraemos SOLO las máquinas que actualmente están circulando hoy
        $flotaEnRuta = Recorrido::with(['vehiculo', 'conductor'])
            ->where('estado', 'en_ruta')
            ->whereDate('hora_salida', Carbon::now('America/Santiago')->toDateString())
            ->get();

        $cantidadActivas = $flotaEnRuta->count();

        // 2. Aquí simulamos temporalmente las coordenadas de los vehículos. 
        // Cuando habilitemos la telemetría del conductor, estos datos vendrán directamente de la BD.
        $coordenadasSimuladas = [
            [
                'id' => 'Micro 01',
                'patente' => 'HY-KZ-16',
                'conductor' => 'Patricio',
                'lat' => -33.691350,
                'lng' => -71.216721,
                'status' => 'En tránsito normal',
                'velocidad' => '65 km/h'
            ],
            [
                'id' => 'Micro 02',
                'patente' => 'CD-EF-34',
                'conductor' => 'Arturo',
                'lat' => -33.774302,
                'lng' => -71.240641,
                'status' => 'Detenido en parada',
                'velocidad' => '0 km/h'
            ]
        ];

        // Solo enviamos coordenadas si realmente hay micros en estado 'en_ruta' en su BD
        $datosMapa = $cantidadActivas > 0 ? $coordenadasSimuladas : [];

        return view('admin.mapa', compact('cantidadActivas', 'datosMapa'));
    }
}

<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifaControlador extends Controller
{
    public function index()
    {
        // Extraemos las tarifas vinculando la tabla de rutas para obtener el nombre completo
        $tarifas = DB::table('tarifas_tramos')
            ->join('rutas', 'tarifas_tramos.ruta_id', '=', 'rutas.id')
            ->select(
                'rutas.nombre_ruta',
                'tarifas_tramos.punto_origen',
                'tarifas_tramos.punto_destino',
                'tarifas_tramos.precio',
                'tarifas_tramos.tipo_tarifa'
            )
            ->orderBy('tarifas_tramos.precio', 'desc')
            ->get();

        return view('cliente.tarifas', compact('tarifas'));
    }
}

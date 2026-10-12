<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MapaControlador extends Controller
{
    public function index()
    {
        $ruta = DB::table('rutas')
            ->where('nombre_ruta', 'Culiprán - Melipilla')
            ->first();

        // Convertimos el JSON de Supabase a un arreglo de PHP de forma segura
        $coordenadasArray = $ruta ? json_decode($ruta->trayecto_coordenadas, true) : [];

        return view('cliente.mapa', compact('coordenadasArray'));
    }
}

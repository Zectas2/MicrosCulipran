<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RutaControlador extends Controller
{
    public function index()
    {
        // Obtenemos todas las rutas disponibles
        $rutas = DB::table('rutas')->get();

        // Decodificamos el JSONB para transformarlo en un objeto utilizable por Blade
        foreach ($rutas as $ruta) {
            $ruta->trayecto = json_decode($ruta->trayecto_coordenadas);
        }

        return view('cliente.rutas', compact('rutas'));
    }
}

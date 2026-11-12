<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TiemposControlador extends Controller
{
    public function index()
    {
        // Estructura de tramos (Preparada para migrar a Base de Datos en el futuro)
        $tramos = [
            (object)[
                'nombre'  => 'Tramo Inicial',
                'color'   => 'text-primario',
                'origen'  => 'Terminal',
                'destino' => 'Cruce Arañas',
                'minutos' => 10
            ],
            (object)[
                'nombre'  => 'Tramo Medio',
                'color'   => 'text-acento',
                'origen'  => 'Cruce',
                'destino' => 'San Manuel',
                'minutos' => 15
            ],
            (object)[
                'nombre'  => 'Tramo Final',
                'color'   => 'text-primario',
                'origen'  => 'San Manuel',
                'destino' => 'Melipilla',
                'minutos' => 20
            ],
        ];

        // Cálculo matemático automático del total de viaje
        $tiempoTotal = collect($tramos)->sum('minutos');

        return view('cliente.tiempos', compact('tramos', 'tiempoTotal'));
    }
}

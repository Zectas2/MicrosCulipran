<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\User;
use App\Models\Ruta;
use App\Models\Recorrido;
use Illuminate\Http\Request;

class PanelAdministracionControlador extends Controller
{
    // Dashboard Principal del Administrador (RF-016)
    public function index()
    {
        // Estadísticas operacionales básicas para el dashboard
        $totalVehiculos = Vehiculo::count();
        $totalConductores = User::where('rol', 'conductor')->count();
        $totalRutas = Ruta::count();
        $recorridosActivos = Recorrido::where('estado', 'en_curso')->count();

        return view('admin.index', compact(
            'totalVehiculos',
            'totalConductores',
            'totalRutas',
            'recorridosActivos'
        ));
    }

    // Listado de Micros/Vehículos
    public function listarMicros()
    {
        $vehiculos = Vehiculo::all();
        return view('admin.micros', compact('vehiculos'));
    }

    // Listado de Choferes/Conductores
    public function listarChoferes()
    {
        $choferes = User::where('rol', 'conductor')->get();
        return view('admin.choferes', compact('choferes'));
    }

    // Vista de Mapa Operativo Administrativo
    public function mapa()
    {
        return view('admin.mapa');
    }

    // Vista de Inteligencia de Negocios / Indicadores (RF-014, RF-016)
    public function bi()
    {
        return view('admin.bi');
    }
}

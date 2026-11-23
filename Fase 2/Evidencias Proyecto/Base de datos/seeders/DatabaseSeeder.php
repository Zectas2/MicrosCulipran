<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Ruta;
use App\Models\TarifaTramo;
use App\Models\Recorrido;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Usuario Administrador 
        $admin = User::create([
            'nombre' => 'Ignacio Cornejo',
            'email' => 'admin@microsculipran.cl',
            'password' => Hash::make('Completos2212'),
            'rol' => 'administrador',
        ]);

        // 2. Crear Usuario Conductor para el cliente
        $conductor = User::create([
            'nombre' => 'Don Arturo',
            'email' => 'arturo@microsculipran.cl',
            'password' => Hash::make('conductor123'),
            'rol' => 'conductor',
        ]);

        // 3. Crear Vehículo Operativo
        $vehiculo = Vehiculo::create([
            'patente' => 'AB1234',
            'capacidad' => 30,
            'estado' => 'operativo',
        ]);

        // 4. Crear Ruta Principal
        $ruta = Ruta::create([
            'nombre_ruta' => 'Culiprán - Centro de Melipilla',
            'origen' => 'Culiprán',
            'destino' => 'Centro de Melipilla',
            'trayecto_coordenadas' => [
                ['lat' => -33.7225, 'lng' => -71.2133], // Coordenada de ejemplo (Culiprán)
                ['lat' => -33.6844, 'lng' => -71.2147], // Coordenada de ejemplo (Centro Melipilla)
            ],
        ]);

        // 5. Crear Tarifas Dinámicas por Tramos
        TarifaTramo::create([
            'ruta_id' => $ruta->id,
            'punto_origen' => 'Culiprán',
            'punto_destino' => 'El Molino',
            'precio' => 500,
        ]);

        TarifaTramo::create([
            'ruta_id' => $ruta->id,
            'punto_origen' => 'El Molino',
            'punto_destino' => 'Centro de Melipilla',
            'precio' => 600,
        ]);

        TarifaTramo::create([
            'ruta_id' => $ruta->id,
            'punto_origen' => 'Culiprán',
            'punto_destino' => 'Centro de Melipilla',
            'precio' => 1000,
        ]);

        // 6. Programar un Recorrido de prueba para las próximas 2 horas
        Recorrido::create([
            'ruta_id' => $ruta->id,
            'vehiculo_id' => $vehiculo->id,
            'conductor_id' => $conductor->id,
            'hora_salida' => Carbon::now()->addHours(2),
            'estado' => 'programado',
        ]);
    }
}
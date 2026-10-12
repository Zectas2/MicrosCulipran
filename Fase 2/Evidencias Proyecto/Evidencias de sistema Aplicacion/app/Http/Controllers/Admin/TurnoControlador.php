<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TurnoControlador extends Controller
{
    public function create()
    {
        $conductores = DB::table('usuarios')->where('rol', 'conductor')->select('id', 'nombre', 'email')->get();
        $vehiculos = DB::table('vehiculos')->where('estado', 'operativo')->select('id', 'patente', 'capacidad')->get();
        return view('admin.asignar-turno', compact('conductores', 'vehiculos'));
    }

    public function store(Request $request)
    {
        // 1. Validar que los datos lleguen correctamente
        $request->validate([
            'conductor_id' => 'required|uuid',
            'vehiculo_id'  => 'required|uuid',
            'dias'         => 'required|array',
            'salidas'      => 'required|array'
        ]);

        // 2. Obtener la ruta predeterminada (Asumiremos la primera ruta registrada para este módulo)
        $ruta = DB::table('rutas')->first();

        if (!$ruta) {
            return back()->with('error', 'No hay rutas configuradas en el sistema.');
        }

        $inserciones = [];

        // 3. Algoritmo de agendamiento sin desfases UTC
        foreach ($request->dias as $diaIso) {
            foreach ($request->salidas as $hora) {
                if (empty($hora)) continue;

                // Creamos la fecha directamente en el huso horario local
                $fechaSalida = Carbon::parse($hora, 'America/Santiago')
                    ->startOfWeek()
                    ->addDays($diaIso - 1)
                    ->setTimeFromTimeString($hora);

                if ($fechaSalida->isPast()) {
                    $fechaSalida->addWeek();
                }

                $inserciones[] = [
                    'id'           => Str::uuid(),
                    'ruta_id'      => $ruta->id,
                    'vehiculo_id'  => $request->vehiculo_id,
                    'conductor_id' => $request->conductor_id,
                    // Se formatea a string para que la DB lo guarde tal cual sin reconversión
                    'hora_salida'  => $fechaSalida->format('Y-m-d H:i:s'),
                    'estado'       => 'programado',
                    'created_at'   => now('America/Santiago')->format('Y-m-d H:i:s'),
                    'updated_at'   => now('America/Santiago')->format('Y-m-d H:i:s'),
                ];
            }
        }

        // 4. Inyección masiva en la base de datos para optimizar recursos
        DB::table('recorridos')->insert($inserciones);

        // 5. Redireccionar al panel con un mensaje de éxito
        return redirect('/admin')->with('exito', 'La cartola semanal ha sido programada con éxito.');
    }

    public function index()
    {
        // Extraemos todos los recorridos en estado "programado" (futuros)
        $turnosProgramados = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->join('usuarios', 'recorridos.conductor_id', '=', 'usuarios.id')
            ->where('recorridos.estado', 'programado')
            ->select(
                'recorridos.id',
                'recorridos.hora_salida',
                'vehiculos.patente',
                'usuarios.nombre as conductor'
            )
            ->orderBy('recorridos.hora_salida', 'asc')
            ->get()
            ->map(function ($turno) {
                // Formateamos la fecha para que sea legible en la vista
                $fecha = Carbon::parse($turno->hora_salida);
                $turno->dia = $fecha->locale('es')->isoFormat('dddd D \d\e MMMM');
                $turno->hora = $fecha->format('H:i');
                return $turno;
            });

        return view('admin.ver-turnos', compact('turnosProgramados'));
    }

    public function destroy($id)
    {
        // Eliminamos el recorrido seleccionado de la base de datos
        DB::table('recorridos')->where('id', $id)->delete();

        return back()->with('exito', 'El turno ha sido cancelado y eliminado del sistema.');
    }
}

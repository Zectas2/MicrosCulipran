<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PanelControlador extends Controller
{
    public function index()
    {
        $hoy = Carbon::today('America/Santiago');
        $conductorId = Auth::id(); // Obtenemos el ID del conductor que inició sesión

        // Buscamos solo los recorridos de hoy asignados a este conductor
        $turnosHoy = DB::table('recorridos')
            ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
            ->where('recorridos.conductor_id', $conductorId)
            ->whereDate('recorridos.hora_salida', $hoy)
            ->select(
                'recorridos.id',
                'recorridos.hora_salida',
                'recorridos.estado',
                'vehiculos.patente'
            )
            ->orderBy('recorridos.hora_salida', 'asc')
            ->get()
            ->map(function ($turno) {
                // Formateamos la hora para la interfaz
                $turno->horaFormateada = Carbon::parse($turno->hora_salida)->timezone('America/Santiago')->format('H:i');
                return $turno;
            });

        return view('conductor.index', compact('turnosHoy'));
    }

    public function iniciarRuta(Request $request, $id)
    {
        // Actualizamos el estado y marcamos el segundo exacto en que inició la marcha
        DB::table('recorridos')
            ->where('id', $id)
            ->where('conductor_id', Auth::id()) // Medida de seguridad adicional
            ->update([
                'estado' => 'en_ruta',
                'hora_salida' => Carbon::now('America/Santiago')->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now('America/Santiago')->format('Y-m-d H:i:s')
            ]);

        return back()->with('exito', '¡Ruta iniciada! Conduzca con precaución.');
    }

    public function reportarPana($id)
    {
        // 1. Buscamos el recorrido actual y validamos que pertenezca a este conductor
        $recorrido = DB::table('recorridos')
            ->where('id', $id)
            ->where('conductor_id', Auth::id())
            ->first();

        if ($recorrido) {
            // 2. Cambiamos el estado del vehículo a 'en_mantencion'
            DB::table('vehiculos')
                ->where('id', $recorrido->vehiculo_id)
                ->update([
                    'estado' => 'en_mantencion',
                    'updated_at' => Carbon::now('America/Santiago')
                ]);

            return back()->with('alerta', 'Se ha notificado la falla mecánica a la central. Permanezca en el lugar.');
        }

        return back()->with('error', 'No se pudo procesar la solicitud.');
    }

    public function finalizarRuta($id)
    {
        // Actualizamos el estado del recorrido a finalizado
        $actualizado = DB::table('recorridos')
            ->where('id', $id)
            ->where('conductor_id', Auth::id())
            ->update([
                'estado' => 'finalizado',
                'updated_at' => Carbon::now('America/Santiago')
            ]);

        if ($actualizado) {
            return back()->with('exito', 'Ruta completada exitosamente. Buen trabajo.');
        }

        return back()->with('error', 'No se pudo finalizar la ruta.');
    }

    // Asegúrese de agregar este import en la parte superior del archivo si no está:
    // use App\Events\UbicacionTransmitida;

    public function transmitirUbicacion(Request $request, $id)
    {
        try {
            $request->validate([
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'velocidad' => 'nullable|numeric'
            ]);

            $conductorId = Auth::id();

            // 1. Validamos que el recorrido exista, esté en ruta y pertenezca al conductor actual
            $recorrido = DB::table('recorridos')
                ->join('vehiculos', 'recorridos.vehiculo_id', '=', 'vehiculos.id')
                ->where('recorridos.id', $id)
                ->where('recorridos.conductor_id', $conductorId)
                ->where('recorridos.estado', 'en_ruta')
                ->select('recorridos.id as recorrido_id', 'vehiculos.id as vehiculo_id', 'vehiculos.patente')
                ->first();

            if (!$recorrido) {
                return response()->json(['error' => 'Recorrido no válido o inactivo'], 403);
            }

            // 2. Emitimos el evento de WebSocket instantáneo para los pasajeros
            event(new \App\Events\UbicacionTransmitida(
                $recorrido->recorrido_id,
                $recorrido->vehiculo_id,
                $recorrido->patente,
                $request->lat,
                $request->lng,
                $request->velocidad
            ));

            // 3. Estrategia de optimización (Inteligencia de Negocios): 
            // Solo guardamos en la base de datos si ha pasado más de 1 minuto desde el último registro
            $ultimoRegistro = DB::table('ubicacion_vehiculos')
                ->where('recorrido_id', $recorrido->recorrido_id)
                ->orderBy('created_at', 'desc')
                ->first();

            $guardarEnBD = false;

            if (!$ultimoRegistro) {
                $guardarEnBD = true;
            } else {
                $ultimaVez = Carbon::parse($ultimoRegistro->created_at);
                if (Carbon::now('America/Santiago')->diffInSeconds($ultimaVez) >= 60) {
                    $guardarEnBD = true;
                }
            }

            if ($guardarEnBD) {
                DB::table('ubicacion_vehiculos')->insert([
                    'recorrido_id' => $recorrido->recorrido_id,
                    'latitud' => $request->lat,
                    'longitud' => $request->lng,
                    'velocidad' => $request->velocidad ?? 0,
                    'created_at' => Carbon::now('America/Santiago'),
                    'updated_at' => Carbon::now('America/Santiago')
                ]);
            }

            // AJUSTE ESTRATÉGICO: Desactiva el escudo SSL interno solo para esta petición
            config(['broadcasting.connections.reverb.client_options.verify' => false]);

            // 2. Emitimos el evento de WebSocket instantáneo para los pasajeros
            event(new \App\Events\UbicacionTransmitida(
                $recorrido->recorrido_id,
                $recorrido->vehiculo_id,
                $recorrido->patente,
                $request->lat,
                $request->lng,
                $request->velocidad
            ));

            return response()->json(['status' => 'Ubicación transmitida y procesada correctamente']);
        } catch (\Exception $e) {
            // AQUI ESTÁ EL DIAGNÓSTICO
            return response()->json([
                'error_detectado' => 'Falla en el servidor',
                'mensaje_tecnico' => $e->getMessage(),
                'archivo_responsable' => $e->getFile(),
                'linea_de_codigo' => $e->getLine()
            ], 500);
        }
    }
}

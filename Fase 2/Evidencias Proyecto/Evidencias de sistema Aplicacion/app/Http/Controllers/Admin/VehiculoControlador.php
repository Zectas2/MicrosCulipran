<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehiculoControlador extends Controller
{
    public function index()
    {
        // Obtenemos toda la flota ordenada por patente
        $vehiculos = DB::table('vehiculos')->orderBy('patente', 'asc')->get();
        return view('admin.micros', compact('vehiculos'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        // Validamos y actualizamos el estado de la máquina
        $request->validate([
            'estado' => 'required|string|in:operativo,en_mantencion,fuera_de_servicio'
        ]);

        DB::table('vehiculos')
            ->where('id', $id)
            ->update([
                'estado' => $request->estado,
                'updated_at' => Carbon::now('America/Santiago')
            ]);

        return back()->with('exito', 'El estado de la máquina ha sido actualizado correctamente.');
    }

    public function create()
    {
        return view('admin.agregar-micro');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patente'   => 'required|string|max:15|unique:vehiculos,patente',
            'marca'     => 'required|string|max:255',
            'anio'      => 'required|integer',
            'capacidad' => 'required|integer',
        ]);

        DB::table('vehiculos')->insert([
            'patente'    => strtoupper($request->patente),
            'marca'      => $request->marca, // Usaremos el campo 'marca' para Marca/Modelo
            'anio'       => $request->anio,
            'capacidad'  => $request->capacidad,
            'estado'     => 'operativo', // Por defecto, una micro nueva está operativa
            'created_at' => Carbon::now('America/Santiago'),
            'updated_at' => Carbon::now('America/Santiago'),
        ]);

        return redirect()->route('admin.micros.index')
            ->with('exito', 'La unidad ' . strtoupper($request->patente) . ' ha sido registrada en la flota.');
    }

    public function edit($id)
    {
        $vehiculo = DB::table('vehiculos')->where('id', $id)->first();

        if (!$vehiculo) {
            return redirect()->route('admin.micros.index')->with('error', 'El vehículo solicitado no existe.');
        }

        return view('admin.editar-micro', compact('vehiculo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marca'     => 'required|string|max:255',
            'anio'      => 'required|integer',
            'capacidad' => 'required|integer',
            'estado'    => 'required|string|in:operativo,en_mantencion,fuera_de_servicio',
        ]);

        DB::table('vehiculos')
            ->where('id', $id)
            ->update([
                'marca'      => $request->marca,
                'anio'       => $request->anio,
                'capacidad'  => $request->capacidad,
                'estado'     => $request->estado,
                'updated_at' => Carbon::now('America/Santiago'),
            ]);

        return redirect()->route('admin.micros.index')
            ->with('exito', 'La ficha técnica de la unidad ha sido actualizada.');
    }
}

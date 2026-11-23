<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehiculo; // Importamos el modelo Eloquent

class VehiculoControlador extends Controller
{
    public function index()
    {
        // Eloquent: Obtenemos toda la flota ordenada por patente
        $vehiculos = Vehiculo::orderBy('patente', 'asc')->get();
        return view('admin.micros', compact('vehiculos'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:operativo,en_mantencion,fuera_de_servicio'
        ]);

        // Eloquent actualiza automáticamente el campo 'updated_at'
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update(['estado' => $request->estado]);

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

        // Eloquent: create() dispara la generación del UUID y las fechas (timestamps) automáticamente
        Vehiculo::create([
            'patente'   => strtoupper($request->patente),
            'marca'     => $request->marca,
            'anio'      => $request->anio,
            'capacidad' => $request->capacidad,
            'estado'    => 'operativo',
        ]);

        return redirect()->route('admin.micros.index')
            ->with('exito', 'La unidad ' . strtoupper($request->patente) . ' ha sido registrada en la flota.');
    }

    public function edit($id)
    {
        // Eloquent: fail() arroja un error 404 automático si el ID no existe
        $vehiculo = Vehiculo::findOrFail($id);
        return view('admin.editar-micro', compact('vehiculo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'estado'    => 'required|string',
            'marca'     => 'required|string|max:255',
            'anio'      => 'required|integer|min:2004|max:' . (date('Y') + 1),
            'capacidad' => 'required|integer|min:10',
        ]);

        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update([
            'estado'    => $request->estado,
            'marca'     => $request->marca,
            'anio'      => $request->anio,
            'capacidad' => $request->capacidad,
        ]);

        return redirect('/admin/micros')->with('exito', 'Ficha de la máquina ' . $vehiculo->patente . ' actualizada correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TarifaControlador extends Controller
{
    public function index()
    {
        // Extraemos todas las tarifas
        $todasLasTarifas = DB::table('tarifas_tramos')
            ->orderBy('precio', 'desc')
            ->get();

        // Clasificamos matemáticamente las tarifas
        $tarifasGenerales = $todasLasTarifas->where('tipo_tarifa', 'General');
        $tarifasEspeciales = $todasLasTarifas->where('tipo_tarifa', '!=', 'General'); // Escolar y Adulto Mayor

        return view('admin.tarifas', compact('tarifasGenerales', 'tarifasEspeciales'));
    }

    public function update(Request $request)
    {
        // Validamos que se envíe un array de tarifas y que sean números válidos
        $request->validate([
            'tarifas'   => 'required|array',
            'tarifas.*' => 'required|integer|min:0',
        ]);

        $ahora = Carbon::now('America/Santiago');

        // Iteramos sobre cada tarifa enviada (el ID como llave, el precio como valor)
        foreach ($request->tarifas as $id => $precio) {
            DB::table('tarifas_tramos')
                ->where('id', $id)
                ->update([
                    'precio'     => $precio,
                    'updated_at' => $ahora
                ]);
        }

        return back()->with('exito', 'El tarifario completo ha sido actualizado con éxito para todos los tramos.');
    }
}

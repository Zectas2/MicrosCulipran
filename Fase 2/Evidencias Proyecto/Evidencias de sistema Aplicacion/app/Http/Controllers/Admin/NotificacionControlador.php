<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;

class NotificacionControlador extends Controller
{
    public function create()
    {
        return view('admin.crear-notificacion');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'   => 'required|in:info,warning,danger',
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'required|string',
        ]);

        Notificacion::create([
            'tipo'   => $request->tipo,
            'titulo' => $request->titulo,
            'cuerpo' => $request->cuerpo,
        ]);

        return redirect()->back()->with('exito', 'Comunicado emitido con éxito a toda la red.');
    }
}

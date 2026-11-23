<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChoferControlador extends Controller
{
    public function index()
    {
        $choferes = DB::table('usuarios')
            ->where('rol', 'conductor')
            ->orderBy('nombre', 'asc')
            ->get();

        return view('admin.choferes', compact('choferes'));
    }

    public function create()
    {
        return view('admin.agregar-chofer');
    }

    public function store(Request $request)
    {
        // 1. Validación estricta de los datos entrantes
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'rut'      => 'required|string|max:20',
            'edad'     => 'required|integer',
            'telefono' => 'required|string|max:20',
        ]);

        // 2. Generación automática de credenciales corporativas
        // Ej: Para "Juan Pérez", genera "juan.1234@microsculipran.cl"
        $primerNombre = strtolower(explode(' ', $request->nombre)[0]);
        $prefijoRut = substr(str_replace('.', '', $request->rut), 0, 4);
        $emailCorporativo = $primerNombre . '.' . $prefijoRut . '@microsculipran.cl';

        // 3. Inserción en la base de datos con contraseña Bcrypt segura
        DB::table('usuarios')->insert([
            'id'         => Str::uuid(),
            'nombre'     => $request->nombre,
            'email'      => $emailCorporativo,
            'password'   => Hash::make('temporal123'),
            'rol'        => 'conductor',
            'rut'        => $request->rut,
            'edad'       => $request->edad,
            'telefono'   => $request->telefono,
            'created_at' => Carbon::now('America/Santiago'),
            'updated_at' => Carbon::now('America/Santiago'),
        ]);

        // 4. Redirección con mensaje de éxito
        return redirect()->route('admin.choferes.index')
            ->with('exito', 'Chofer registrado exitosamente. Email: ' . $emailCorporativo . ' | Contraseña: temporal123');
    }

    public function edit($id)
    {
        // Buscamos al chofer específico por su ID
        $chofer = DB::table('usuarios')
            ->where('id', $id)
            ->where('rol', 'conductor')
            ->first();

        if (!$chofer) {
            return redirect()->route('admin.choferes.index')->with('error', 'El chofer solicitado no existe o no tiene los permisos adecuados.');
        }

        return view('admin.editar-chofer', compact('chofer'));
    }

    public function update(Request $request, $id)
    {
        // Validamos los datos modificados, incluyendo el estado
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'rut'      => 'required|string|max:20',
            'edad'     => 'required|integer',
            'telefono' => 'required|string|max:20',
            'estado'   => 'required|string|in:activo,vacaciones,licencia,inactivo', // Nueva validación
        ]);

        // Actualizamos el registro en la base de datos
        DB::table('usuarios')
            ->where('id', $id)
            ->update([
                'nombre'     => $request->nombre,
                'rut'        => $request->rut,
                'edad'       => $request->edad,
                'telefono'   => $request->telefono,
                'estado'     => $request->estado, // Actualizamos el estado
                'updated_at' => Carbon::now('America/Santiago'),
            ]);

        // Redirigimos al directorio con un mensaje de éxito
        return redirect()->route('admin.choferes.index')
            ->with('exito', 'Los datos del chofer ' . $request->nombre . ' han sido actualizados de forma exitosa.');
    }
}

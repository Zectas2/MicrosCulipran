<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AutenticacionControlador;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Módulo Cliente) - Sin restricciones
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('cliente.index');
});
Route::get('/mapa', function () {
    return view('cliente.mapa');
});
Route::get('/horarios', function () {
    return view('cliente.horarios');
});
Route::get('/rutas', function () {
    return view('cliente.rutas');
});
Route::get('/tarifas', function () {
    return view('cliente.tarifas');
});
Route::get('/tiempos', function () {
    return view('cliente.tiempos');
});
Route::get('/notificaciones', function () {
    return view('cliente.notificaciones');
});
Route::get('/notificaciones/detalle', function () {
    return view('cliente.notificacion-detalle');
});

/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
// El middleware 'guest' evita que alguien que ya ingresó vuelva a ver el login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AutenticacionControlador::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AutenticacionControlador::class, 'ingresar']);
});

// Cerrar sesión requiere estar autenticado
Route::post('/logout', [AutenticacionControlador::class, 'cerrarSesion'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS RESTRINGIDAS: ADMINISTRADOR
|--------------------------------------------------------------------------
*/
// Todas las rutas aquí dentro exigen el rol 'administrador' y tienen el prefijo '/admin'
Route::middleware(['rol:administrador'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    });
    Route::get('/mapa', function () {
        return view('admin.mapa');
    });
    Route::get('/agregar-micro', function () {
        return view('admin.agregar-micro');
    });
    Route::get('/tarifas', function () {
        return view('admin.tarifas');
    });
    Route::get('/micros', function () {
        return view('admin.micros');
    });
    Route::get('/choferes', function () {
        return view('admin.choferes');
    });
    Route::get('/agregar-chofer', function () {
        return view('admin.agregar-chofer');
    });
    Route::get('/bi', function () {
        return view('admin.bi');
    });
    Route::get('/notificaciones', function () {
        return view('admin.crear-notificacion');
    });
    Route::get('/asignar-turno', function () {
        return view('admin.asignar-turno');
    });
    Route::get('/editar-micro', function () {
        return view('admin.editar-micro');
    });
    Route::get('/editar-chofer', function () {
        return view('admin.editar-chofer');
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS RESTRINGIDAS: CONDUCTOR
|--------------------------------------------------------------------------
*/
// Todas las rutas aquí dentro exigen el rol 'conductor' y tienen el prefijo '/conductor'
Route::middleware(['rol:conductor'])->prefix('conductor')->group(function () {
    Route::get('/', function () {
        return view('conductor.index');
    });
    Route::get('/itinerario', function () {
        return view('conductor.itinerario');
    });
});



use App\Http\Controllers\Admin\PanelAdministracionControlador;

Route::middleware(['rol:administrador'])->prefix('admin')->group(function () {
    Route::get('/', [PanelAdministracionControlador::class, 'index']);
    Route::get('/mapa', [PanelAdministracionControlador::class, 'mapa']);
    Route::get('/micros', [PanelAdministracionControlador::class, 'listarMicros']);
    Route::get('/choferes', [PanelAdministracionControlador::class, 'listarChoferes']);
    Route::get('/bi', [PanelAdministracionControlador::class, 'bi']);

    // Vistas de formularios y gestión restante
    Route::get('/agregar-micro', function () {
        return view('admin.agregar-micro');
    });
    Route::get('/tarifas', function () {
        return view('admin.tarifas');
    });
    Route::get('/agregar-chofer', function () {
        return view('admin.agregar-chofer');
    });
    Route::get('/notificaciones', function () {
        return view('admin.crear-notificacion');
    });
    Route::get('/asignar-turno', function () {
        return view('admin.asignar-turno');
    });
    Route::get('/editar-micro', function () {
        return view('admin.editar-micro');
    });
    Route::get('/editar-chofer', function () {
        return view('admin.editar-chofer');
    });
});

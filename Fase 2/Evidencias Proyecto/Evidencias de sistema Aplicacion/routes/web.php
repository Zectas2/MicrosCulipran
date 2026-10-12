<?php

use Illuminate\Support\Facades\Route;

// Importación de Controladores de Autenticación
use App\Http\Controllers\Auth\AutenticacionControlador;

// Importación de Controladores de Administrador
use App\Http\Controllers\Admin\PanelAdministracionControlador;
use App\Http\Controllers\Admin\TurnoControlador;
use App\Http\Controllers\Admin\VehiculoControlador;
use App\Http\Controllers\Admin\ChoferControlador;
use App\Http\Controllers\Admin\TarifaControlador as AdminTarifaControlador;
use App\Http\Controllers\Admin\NotificacionControlador as AdminNotificacionControlador;

// Importación de Controladores de Conductor
use App\Http\Controllers\Conductor\PanelControlador;


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Módulo Cliente) - Sin restricciones
|--------------------------------------------------------------------------
*/

// Vistas estáticas
Route::get('/', [\App\Http\Controllers\Publico\InicioControlador::class, 'index'])->name('inicio');
Route::get('/tiempos', function () {
    return view('cliente.tiempos');
});

// Vistas dinámicas usando Rutas Absolutas (Blindaje contra errores de importación)
Route::get('/tarifas', [\App\Http\Controllers\Publico\TarifaControlador::class, 'index'])->name('tarifas.index');
Route::get('/rutas', [\App\Http\Controllers\Publico\RutaControlador::class, 'index'])->name('rutas.index');
Route::get('/mapa', [\App\Http\Controllers\Publico\MapaControlador::class, 'index'])->name('mapa.index');
Route::get('/horarios', [\App\Http\Controllers\Publico\HorarioControlador::class, 'index'])->name('horarios.index');

// Notificaciones
Route::get('/notificaciones', [\App\Http\Controllers\Publico\NotificacionControlador::class, 'index'])->name('notificaciones.index');
Route::get('/notificaciones/{id}', [\App\Http\Controllers\Publico\NotificacionControlador::class, 'show'])->name('notificaciones.show');


/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AutenticacionControlador::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AutenticacionControlador::class, 'ingresar']);
});

Route::post('/logout', [AutenticacionControlador::class, 'cerrarSesion'])->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS RESTRINGIDAS: ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['rol:administrador'])->prefix('admin')->group(function () {

    // Panel General y KPIs
    Route::get('/', [PanelAdministracionControlador::class, 'index']);
    Route::get('/mapa', [PanelAdministracionControlador::class, 'mapa']);
    Route::get('/bi', [PanelAdministracionControlador::class, 'bi']);
    Route::get('/mapa', [\App\Http\Controllers\Admin\MapaAdminControlador::class, 'index']);

    // Gestión de Choferes
    Route::get('/choferes', [ChoferControlador::class, 'index'])->name('admin.choferes.index');
    Route::get('/agregar-chofer', [ChoferControlador::class, 'create'])->name('admin.choferes.create');
    Route::post('/agregar-chofer', [ChoferControlador::class, 'store'])->name('admin.choferes.store');
    Route::get('/editar-chofer/{id}', [ChoferControlador::class, 'edit'])->name('admin.choferes.edit');
    Route::put('/editar-chofer/{id}', [ChoferControlador::class, 'update'])->name('admin.choferes.update');

    // Gestión de Flota (Vehículos)
    Route::get('/micros', [VehiculoControlador::class, 'index'])->name('admin.micros.index');
    Route::post('/micros/{id}/estado', [VehiculoControlador::class, 'cambiarEstado'])->name('admin.micros.estado');
    Route::get('/agregar-micro', [VehiculoControlador::class, 'create'])->name('admin.micros.create');
    Route::post('/agregar-micro', [VehiculoControlador::class, 'store'])->name('admin.micros.store');
    Route::get('/editar-micro/{id}', [VehiculoControlador::class, 'edit'])->name('admin.micros.edit');
    Route::put('/editar-micro/{id}', [VehiculoControlador::class, 'update'])->name('admin.micros.update');

    // Gestión de Turnos y Despacho
    Route::get('/asignar-turno', [TurnoControlador::class, 'create'])->name('admin.turno.create');
    Route::post('/asignar-turno', [TurnoControlador::class, 'store'])->name('admin.turno.store');
    Route::get('/ver-turnos', [TurnoControlador::class, 'index'])->name('admin.turno.index');
    Route::delete('/turnos/{id}', [TurnoControlador::class, 'destroy'])->name('admin.turno.destroy');

    // Gestión de Tarifas
    Route::get('/tarifas', [AdminTarifaControlador::class, 'index'])->name('admin.tarifas.index');
    Route::post('/tarifas', [AdminTarifaControlador::class, 'update'])->name('admin.tarifas.update');

    // Comunicados y Notificaciones
    Route::get('/notificaciones', [AdminNotificacionControlador::class, 'create'])->name('admin.notificaciones.create');
    Route::post('/notificaciones', [AdminNotificacionControlador::class, 'store'])->name('admin.notificaciones.store');

    Route::get('/bi', function () {
    return view('admin.bi');
});
});


/*
|--------------------------------------------------------------------------
| RUTAS RESTRINGIDAS: CONDUCTOR
|--------------------------------------------------------------------------
*/

Route::middleware(['rol:conductor'])->prefix('conductor')->group(function () {

    // Panel Principal y Acciones Operativas
    Route::get('/', [PanelControlador::class, 'index'])->name('conductor.index');
    Route::post('/iniciar-ruta/{id}', [PanelControlador::class, 'iniciarRuta'])->name('conductor.iniciar');
    Route::post('/reportar-pana/{id}', [PanelControlador::class, 'reportarPana'])->name('conductor.pana');
    Route::post('/finalizar-ruta/{id}', [PanelControlador::class, 'finalizarRuta'])->name('conductor.finalizar');

    // Itinerario
    Route::get('/itinerario', function () {
        return view('conductor.itinerario');
    });
});

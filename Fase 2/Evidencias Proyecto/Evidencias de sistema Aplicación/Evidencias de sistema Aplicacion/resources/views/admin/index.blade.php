@extends('layouts.app')
@section('title', 'Panel Administrativo - Micros Culiprán')

@section('content')
<div class="flex flex-col space-y-6 pb-6">

    <!-- Encabezado del Panel -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-texto">Panel de Control</h1>
            <p class="text-texto/70 text-sm">Resumen operativo de la flota</p>
        </div>
        <div class="bg-primario/10 p-3 rounded-2xl">
            <svg class="w-7 h-7 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
    </div>

    <!-- Tarjetas de KPI (Indicadores clave) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-secundario p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center">
            <span class="text-texto/60 text-[10px] font-bold uppercase tracking-wider">Micros en Ruta</span>
            <span class="text-3xl font-extrabold text-acento mt-1">{{ $microsEnRuta ?? 0 }}</span>
        </div>
        <div class="bg-secundario p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center">
            <span class="text-texto/60 text-[10px] font-bold uppercase tracking-wider">Recorridos Hoy</span>
            <span class="text-3xl font-extrabold text-primario mt-1">{{ $recorridosHoy ?? 0 }}</span>
        </div>
        <div class="bg-secundario p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center col-span-2 md:col-span-1">
            <span class="text-texto/60 text-[10px] font-bold uppercase tracking-wider">Alertas del Sistema</span>
            <span class="text-3xl font-extrabold {{ isset($alertas) && $alertas > 0 ? 'text-peligro' : 'text-green-500' }} mt-1">{{ $alertas ?? 0 }}</span>
        </div>
    </div>

    <!-- Módulo de Flota Activa (NUEVO) -->
    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-primario px-5 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                <h3 class="text-secundario font-bold text-lg">Monitoreo de Flota</h3>
            </div>
            <a href="/admin/mapa" class="text-secundario/80 hover:text-secundario text-xs font-bold uppercase tracking-wider">Ver Mapa</a>
        </div>

        <div class="divide-y divide-gray-100">
            @if(isset($flotaActiva) && count($flotaActiva) > 0)
            @foreach($flotaActiva as $recorrido)
            <div class="p-5 flex flex-col space-y-3 {{ $recorrido->bgColor ?? 'bg-white' }} transition-colors">
                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <div class="{{ $recorrido->iconBg ?? 'bg-gray-100' }} p-3 rounded-xl mr-3 border">
                            <svg class="w-6 h-6 {{ $recorrido->iconColor ?? 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-black text-texto text-lg leading-tight">{{ $recorrido->patente ?? 'S/P' }}</p>
                            <p class="text-xs text-texto/60 font-medium">Conductor: {{ $recorrido->conductor ?? 'No asignado' }}</p>
                        </div>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm {{ $recorrido->badgeClass ?? 'bg-gray-200 text-gray-700' }}">
                        {{ $recorrido->textoEstado ?? 'Desconocido' }}
                    </span>
                </div>
                <div class="bg-secundario border {{ $recorrido->bordeClase ?? 'border-gray-200' }} rounded-xl p-3 flex justify-between items-center shadow-inner">
                    <div>
                        <p class="text-[9px] text-texto/50 font-bold uppercase tracking-wide">Hora de Salida</p>
                        <p class="text-sm font-bold text-texto mt-0.5">{{ $recorrido->horaSalidaFormateada ?? '--:--' }} hrs</p>
                    </div>
                    <div class="text-right border-l border-gray-200 pl-3">
                        <p class="text-[9px] text-texto/50 font-bold uppercase tracking-wide">{{ $recorrido->etiquetaTiempo ?? 'Tiempo' }}</p>
                        <p class="text-sm font-black {{ $recorrido->textoTiempoClase ?? 'text-gray-700' }} mt-0.5">
                            {{ $recorrido->valorTiempo ?? '--' }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="p-8 text-center text-gray-500 font-bold bg-white">
                No hay micros operando en ruta en este momento.
            </div>
            @endif
        </div>
    </div>

    <!-- Cuadrícula de Gestión General -->
    <h3 class="font-bold text-texto text-lg mt-4 px-1">Gestión General</h3>
    <div class="grid grid-cols-2 gap-4 pb-2">

        <!-- Botón: Directorio de Flota -->
        <a href="/admin/micros" class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
            <div class="bg-primario/10 p-3 rounded-full mb-2">
                <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-texto">Ver Flota</span>
        </a>

        <!-- Botón: Nómina de Choferes -->
        <a href="/admin/choferes" class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
            <div class="bg-primario/10 p-3 rounded-full mb-2">
                <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-texto">Ver Choferes</span>
        </a>

        <!-- Botón: Agregar Micro -->
        <a href="/admin/agregar-micro" class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
            <div class="bg-primario/10 p-3 rounded-full mb-2">
                <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-texto">Agregar Micro</span>
        </a>

        <!-- NUEVO BOTÓN: Ver Jornadas Programadas -->
        <a href="/admin/ver-turnos" class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
            <div class="bg-acento/10 p-3 rounded-full mb-2">
                <svg class="w-6 h-6 text-acento" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-texto">Ver Jornadas</span>
        </a>

        <!-- Botón: Ajustar Tarifas -->
        <a href="/admin/tarifas" class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
            <div class="bg-primario/10 p-3 rounded-full mb-2">
                <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
            </div>
            <span class="text-xs font-bold text-texto">Ajustar Tarifas</span>
        </a>

        <!-- Botón: Emitir Comunicado -->
        <a href="/admin/notificaciones" class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
            <div class="bg-peligro/10 p-3 rounded-full mb-2">
                <svg class="w-6 h-6 text-peligro" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-bold text-texto uppercase tracking-wider">Emitir Comunicado Oficial</span>
        </a>

    </div>

    <!-- Botón Destacado: Asignar Turno (Despacho Operativo) -->
    <a href="/admin/asignar-turno" class="bg-primario text-secundario p-4 rounded-3xl shadow-md border border-orange-600 flex flex-col items-center justify-center active:scale-95 transition-transform text-center">
        <div class="bg-secundario/20 p-3 rounded-full mb-2">
            <svg class="w-6 h-6 text-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <span class="text-sm font-black uppercase tracking-wider">Asignar Turno a Conductor</span>
    </a>

    <!-- Acceso a Inteligencia de Negocios (BI) -->
    <h3 class="font-bold text-texto text-lg mt-4 px-1">Análisis Estratégico</h3>
    <a href="/admin/bi" class="w-full mt-2 bg-texto text-secundario rounded-3xl p-5 flex items-center justify-between shadow-lg active:scale-95 transition-transform border-b-4 border-gray-900 mb-2">
        <div class="flex items-center">
            <div class="bg-secundario/20 p-3 rounded-2xl mr-4">
                <svg class="w-7 h-7 text-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="text-left">
                <h3 class="font-black text-lg leading-tight">Dashboard BI</h3>
                <p class="text-secundario/70 text-xs mt-1 font-medium">Métricas y rendimiento general</p>
            </div>
        </div>
        <svg class="w-6 h-6 text-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>

</div>
@endsection
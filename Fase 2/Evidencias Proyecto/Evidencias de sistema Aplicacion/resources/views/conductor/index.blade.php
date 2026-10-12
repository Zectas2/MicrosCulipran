@extends('layouts.app')
@section('title', 'Panel de Conductor - Micros Culiprán')

@section('content')

@php
// Extraemos la información del turno actual (el primero que esté programado o en ruta)
$turnoActual = $turnosHoy->whereIn('estado', ['programado', 'en_ruta'])->first();
$vueltasTotales = $turnosHoy->count();
@endphp

<div class="flex flex-col space-y-6 max-w-md mx-auto w-full pb-6">

    <!-- Notificación de Éxito al iniciar ruta -->
    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm text-center">
        <span class="block font-bold text-sm">{{ session('exito') }}</span>
    </div>
    @endif

    <!-- Notificación de Emergencia -->
    @if(session('alerta'))
    <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm text-center mb-4 animate-pulse">
        <span class="block font-black text-sm">{{ session('alerta') }}</span>
    </div>
    @endif

    <!-- Tarjeta Interactiva de Información de Ruta Asignada -->
    @if($turnoActual)
    <a href="/conductor/itinerario" class="block bg-secundario rounded-3xl shadow-sm border border-gray-200 p-5 active:scale-95 transition-transform relative hover:border-primario/50">
        <!-- Ícono indicador de lista/expansión -->
        <div class="absolute top-4 right-4 bg-gray-100 p-1.5 rounded-lg text-texto/40">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
        </div>

        <div class="flex justify-between items-center mb-3 pr-10">
            <span class="text-xs font-bold uppercase tracking-wider text-primario bg-primario/10 px-3 py-1 rounded-full">
                {{ $turnoActual->estado === 'en_ruta' ? 'Ruta Activa' : 'Turno Asignado' }}
            </span>
            <span class="text-xs font-bold text-acento">
                {{ $turnoActual->estado === 'en_ruta' ? 'En Tránsito' : 'Pendiente' }}
            </span>
        </div>

        <h2 class="text-xl font-black text-texto">Culiprán &rarr; Melipilla</h2>

        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between text-sm text-texto/80">
            <div>
                <p class="text-[10px] uppercase font-bold text-texto/50">Máquina</p>
                <p class="font-bold">{{ $turnoActual->patente }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] uppercase font-bold text-texto/50">Hora Programada</p>
                <p class="font-bold">{{ $turnoActual->horaFormateada }} hrs</p>
            </div>
        </div>

        <!-- Indicador visual de más contenido -->
        <div class="mt-4 bg-fondo rounded-xl p-2 text-center border border-gray-100 flex justify-center items-center">
            <span class="text-[10px] font-bold text-texto/60 uppercase tracking-widest">Tocar para ver itinerario completo ({{ $vueltasTotales }} Vueltas)</span>
        </div>
    </a>
    @else
    <!-- Tarjeta de Jornada Finalizada / Sin Turnos -->
    <div class="block bg-secundario rounded-3xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-xl font-black text-texto">Jornada Completada</h2>
        <p class="text-sm text-texto/60 mt-2">No tiene recorridos pendientes para el día de hoy.</p>
    </div>
    @endif


    <!-- Bloque de Botones Operativos -->
    <div class="flex flex-col space-y-4">

        <!-- Botón 1: Iniciar Ruta (Funcional con Formulario) -->
        @if($turnoActual && $turnoActual->estado === 'programado')
        <form action="{{ route('conductor.iniciar', $turnoActual->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-acento text-secundario text-xl font-black py-6 rounded-3xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>INICIAR RUTA</span>
            </button>
        </form>
        @else
        <!-- Botón deshabilitado si ya inició ruta o no tiene turnos -->
        <button disabled class="w-full bg-gray-300 text-gray-500 text-xl font-black py-6 rounded-3xl shadow-sm flex items-center justify-center space-x-3 cursor-not-allowed">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $turnoActual && $turnoActual->estado === 'en_ruta' ? 'RUTA EN CURSO' : 'SIN RUTA PENDIENTE' }}</span>
        </button>
        @endif

        <!-- Botón 2: Finalizar Ruta Actual (Funcional) -->
        @if($turnoActual && $turnoActual->estado === 'en_ruta')
        <form action="{{ route('conductor.finalizar', $turnoActual->id) }}" method="POST" onsubmit="return confirm('¿Confirma que ha llegado al terminal y desea finalizar esta ruta?');">
            @csrf
            <button type="submit" class="w-full bg-texto text-secundario text-base font-bold py-4 rounded-3xl shadow-sm active:scale-95 transition-transform animate-pulse bg-green-700">
                Finalizar Ruta Actual
            </button>
        </form>
        @else
        <!-- Botón deshabilitado si no está en ruta -->
        <button disabled class="w-full bg-gray-300 text-gray-500 text-base font-bold py-4 rounded-3xl shadow-sm cursor-not-allowed opacity-70">
            Finalizar Ruta Actual
        </button>
        @endif

        <!-- Separador visual de seguridad -->
        <div class="pt-4">
            @if($turnoActual)
            <!-- Botón 3: Alerta de Emergencia (Funcional) -->
            <form action="{{ route('conductor.pana', $turnoActual->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de reportar una avería? Esto alertará a la central de forma inmediata.');">
                @csrf
                <button type="submit" class="w-full bg-peligro text-secundario text-lg font-black py-5 rounded-3xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-3 border-2 border-red-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>MICRO EN PANA</span>
                </button>
            </form>
            @else
            <!-- Botón deshabilitado si no hay turno asignado -->
            <button disabled class="w-full bg-red-300 text-secundario text-lg font-black py-5 rounded-3xl shadow-sm flex items-center justify-center space-x-3 border-2 border-red-300 cursor-not-allowed opacity-70">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>MICRO EN PANA</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Nota de Asistencia Operativa -->
    <div class="text-center px-4">
        <p class="text-xs text-texto/50 leading-relaxed">
            Sistema operativo en segundo plano. Su ubicación se transmite de forma automática.
        </p>
    </div>

</div>
@endsection
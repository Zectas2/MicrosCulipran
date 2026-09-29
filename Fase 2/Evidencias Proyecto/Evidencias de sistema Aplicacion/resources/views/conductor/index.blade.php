@extends('layouts.app')
@section('title', 'Panel de Conductor - Micros Culiprán')

@section('content')
<div class="flex flex-col space-y-6 max-w-md mx-auto w-full pb-6">

    <!-- Tarjeta Interactiva de Información de Ruta Asignada -->
    <a href="/conductor/itinerario" class="block bg-secundario rounded-3xl shadow-sm border border-gray-200 p-5 active:scale-95 transition-transform relative hover:border-primario/50">
        <!-- Ícono indicador de lista/expansión -->
        <div class="absolute top-4 right-4 bg-gray-100 p-1.5 rounded-lg text-texto/40">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
        </div>

        <div class="flex justify-between items-center mb-3 pr-10">
            <span class="text-xs font-bold uppercase tracking-wider text-primario bg-primario/10 px-3 py-1 rounded-full">Turno Asignado</span>
            <span class="text-xs font-bold text-acento">Salida en 15 min</span>
        </div>

        <h2 class="text-xl font-black text-texto">Culiprán &rarr; Melipilla</h2>

        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between text-sm text-texto/80">
            <div>
                <p class="text-[10px] uppercase font-bold text-texto/50">Punto de Partida</p>
                <p class="font-bold">Terminal Culiprán</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] uppercase font-bold text-texto/50">Hora Programada</p>
                <p class="font-bold">08:30 hrs</p>
            </div>
        </div>

        <!-- Indicador visual de más contenido -->
        <div class="mt-4 bg-fondo rounded-xl p-2 text-center border border-gray-100 flex justify-center items-center">
            <span class="text-[10px] font-bold text-texto/60 uppercase tracking-widest">Tocar para ver itinerario completo (3 Vueltas)</span>
        </div>
    </a>

    <!-- Bloque de Botones Operativos -->
    <div class="flex flex-col space-y-4">

        <!-- Botón 1: Iniciar Ruta -->
        <button class="w-full bg-acento text-secundario text-xl font-black py-6 rounded-3xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>INICIAR RUTA</span>
        </button>

        <!-- Botón 2: Finalizar Ruta Actual -->
        <button class="w-full bg-texto text-secundario text-base font-bold py-4 rounded-3xl shadow-sm active:scale-95 transition-transform">
            Finalizar Ruta Actual
        </button>

        <!-- Separador visual de seguridad -->
        <div class="pt-4">
            <!-- Botón 3: Alerta de Emergencia -->
            <button class="w-full bg-peligro text-secundario text-lg font-black py-5 rounded-3xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-3 border-2 border-red-700">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>MICRO EN PANA / RETRASO</span>
            </button>
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
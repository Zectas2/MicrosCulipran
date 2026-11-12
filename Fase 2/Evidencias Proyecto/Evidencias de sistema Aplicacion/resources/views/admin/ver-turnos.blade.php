@extends('layouts.app')
@section('title', 'Jornadas Programadas - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-8">

    <!-- Cabecera Ejecutiva -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin" class="bg-gray-100 p-2.5 rounded-full text-texto/60 hover:text-primario hover:bg-primario/10 transition-colors mr-4" title="Volver al Panel">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-texto leading-none">Jornadas Programadas</h1>
                <p class="text-xs font-bold text-texto/50 uppercase tracking-widest mt-1">Directorio de Salidas</p>
            </div>
        </div>
        <!-- Enlace rápido para crear más turnos -->
        <a href="/admin/asignar-turno" class="bg-primario text-secundario px-4 py-2 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-transform flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Programar
        </a>
    </div>

    <!-- Mensaje de confirmación -->
    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="block font-bold text-sm">{{ session('exito') }}</span>
    </div>
    @endif

    <!-- Contenedor de la Línea de Tiempo Logística -->
    <div class="space-y-4">
        @forelse($turnosProgramados as $turno)

        <!-- Tarjeta de Itinerario (Tipo Boleto) -->
        <div class="bg-secundario rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative transition-all hover:shadow-md hover:border-primario/30 flex flex-row">

            <!-- Bloque de Tiempo Destacado (Lado Izquierdo) -->
            <div class="bg-primario/5 px-4 sm:px-6 py-4 flex flex-col justify-center items-center border-r border-gray-100 min-w-[100px] sm:min-w-[120px]">
                <p class="text-[9px] sm:text-[10px] font-bold text-primario/70 uppercase tracking-widest text-center truncate w-full">{{ $turno->dia }}</p>
                <p class="text-xl sm:text-2xl font-black text-primario leading-none mt-1">{{ $turno->hora }}</p>
                <p class="text-[9px] font-bold text-primario/50 uppercase mt-0.5 tracking-widest">HRS</p>
            </div>

            <!-- Bloque de Detalles (Centro y Derecha) -->
            <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row sm:justify-between sm:items-center">

                <!-- Información de Asignación -->
                <div class="flex-1">
                    <div class="flex items-center mb-1.5">
                        <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest bg-gray-200 text-gray-700 mr-2 border border-gray-300 shadow-inner">
                            {{ $turno->patente }}
                        </span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-texto/40 uppercase tracking-wider">Chofer Asignado</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-texto/30 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p class="font-black text-texto text-sm sm:text-base">{{ $turno->conductor }}</p>
                    </div>
                </div>

                <!-- Acción de Cancelación -->
                <div class="mt-3 sm:mt-0 flex justify-end">
                    <form action="{{ route('admin.turno.destroy', $turno->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea cancelar esta salida programada?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-xl active:scale-95 transition-all border border-red-100" title="Cancelar Turno">
                            <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="sm:inline hidden uppercase tracking-wider text-[10px]">Cancelar</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @empty

        <!-- Estado Vacío -->
        <div class="py-12 px-6 text-center bg-secundario rounded-3xl border border-dashed border-gray-300 shadow-sm flex flex-col items-center">
            <div class="bg-gray-50 p-4 rounded-full mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="font-black text-texto text-lg mb-1">Sin turnos en agenda</h3>
            <p class="text-sm font-bold text-texto/50 mb-4">No hay salidas programadas para los próximos días.</p>
            <a href="/admin/asignar-turno" class="bg-primario text-secundario px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-transform flex items-center">
                Programar Nueva Jornada
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
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
                <p class="text-xs font-bold text-texto/50 uppercase tracking-widest mt-1">Agenda de Despachos</p>
            </div>
        </div>
        <!-- Enlace rápido -->
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

    <!-- Contenedor de Agenda -->
    <div class="space-y-4">
        @forelse($turnosProgramados as $turno)

        <!-- Tarjeta de Evento (Agenda Corporativa) -->
        <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100 hover:border-primario/40 transition-all relative overflow-hidden group">

            <!-- Acento Lateral Izquierdo -->
            <div class="absolute left-0 top-0 bottom-0 w-2 bg-primario"></div>

            <div class="pl-2 flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-texto/50 uppercase tracking-widest">{{ $turno->dia }}</p>
                    <h3 class="text-4xl font-black text-texto leading-none tracking-tighter mt-1">
                        {{ $turno->hora }} <span class="text-sm text-texto/40 font-bold tracking-normal">hrs</span>
                    </h3>
                </div>

                <!-- Placa Patente -->
                <div class="bg-gray-100/80 border border-gray-200 px-3 py-1.5 rounded-xl shadow-inner flex flex-col items-center justify-center">
                    <span class="text-[8px] font-bold text-texto/40 uppercase tracking-widest mb-0.5">Máquina</span>
                    <span class="text-xs font-black text-gray-800 uppercase tracking-wider">{{ $turno->patente }}</span>
                </div>
            </div>

            <!-- Divisor -->
            <div class="ml-2 mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">

                <!-- Info Conductor -->
                <div class="flex items-center">
                    <div class="bg-primario/10 p-2.5 rounded-full mr-3">
                        <svg class="w-5 h-5 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-texto/40 uppercase tracking-widest mb-0.5">Chofer Asignado</p>
                        <p class="font-black text-texto text-sm">{{ $turno->conductor }}</p>
                    </div>
                </div>

                <!-- Acción: Cancelar (Diseño integrado) -->
                <form action="{{ route('admin.turno.destroy', $turno->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea cancelar esta salida programada?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 p-2.5 rounded-xl transition-colors active:scale-95 border border-red-100/50" title="Cancelar Turno">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>

            </div>
        </div>
        @empty

        <!-- Estado Vacío Mejorado -->
        <div class="py-12 px-6 text-center bg-secundario rounded-3xl border border-dashed border-gray-300 shadow-sm flex flex-col items-center">
            <div class="bg-gray-50 p-4 rounded-full mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="font-black text-texto text-lg mb-1">Sin turnos en agenda</h3>
            <p class="text-sm font-bold text-texto/50 mb-4">No hay salidas programadas para los próximos días.</p>
            <a href="/admin/asignar-turno" class="bg-primario text-secundario px-6 py-3 rounded-xl text-sm font-bold shadow-md active:scale-95 transition-transform flex items-center hover:bg-gray-800">
                Programar Nueva Jornada
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
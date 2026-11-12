@extends('layouts.app')
@section('title', 'Mi Itinerario - Micros Culiprán')
@section('content')
<div class="flex flex-col space-y-6 pb-6 max-w-md mx-auto w-full">
    <!-- Cabecera de Navegación -->
    <div class="flex items-center mb-2">
        <a href="/conductor" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Mi Programación</h1>
    </div>

    <!-- BLOQUE 1: ITINERARIO DE HOY -->
    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-primario px-5 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-secundario font-bold text-lg">Hoy: Culiprán &rarr; Melipilla</h3>
                <p class="text-secundario/90 text-xs font-medium mt-0.5">Conduciendo máquina: <span class="font-black bg-secundario/20 px-2 py-0.5 rounded ml-1 tracking-widest">{{ $maquinaHoy }}</span></p>
            </div>
        </div>

        <div class="p-5 space-y-4 divide-y divide-gray-100">
            @forelse($turnosHoy as $turno)
            @php
            $horaFmt = \Carbon\Carbon::parse($turno->hora_programada)->timezone('America/Santiago')->format('H:i');
            @endphp

            @if($turno->estado === 'finalizado')
            <!-- Vuelta Completada -->
            <div class="flex items-center justify-between opacity-50 pt-4 first:pt-0">
                <div class="flex items-center">
                    <span class="bg-gray-300 text-texto/60 font-black text-xs px-3 py-2 rounded-lg mr-4">{{ $loop->iteration }}</span>
                    <div>
                        <span class="block font-bold text-texto line-through text-lg">{{ $horaFmt }} hrs</span>
                    </div>
                </div>
                <span class="text-[10px] font-black uppercase text-acento flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg> Completada
                </span>
            </div>
            @elseif($turno->estado === 'en_ruta')
            <!-- Vuelta Actual -->
            <div class="flex items-center justify-between bg-primario/5 -mx-3 p-3 rounded-xl border border-primario/20 relative mt-4">
                <div class="absolute -left-0 top-1/2 transform -translate-y-1/2 w-1.5 h-10 bg-primario rounded-r-md"></div>
                <div class="flex items-center pl-3">
                    <span class="bg-primario text-secundario font-black text-xs px-3 py-2 rounded-lg mr-4">{{ $loop->iteration }}</span>
                    <div>
                        <span class="block font-black text-primario text-xl">{{ $horaFmt }} hrs</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black uppercase text-primario animate-pulse mb-0.5">En Curso</span>
                </div>
            </div>
            @else
            <!-- Vuelta Pendiente -->
            <div class="flex items-center justify-between pt-4 first:pt-0">
                <div class="flex items-center">
                    <span class="bg-gray-100 text-texto/50 font-black text-xs px-3 py-2 rounded-lg mr-4">{{ $loop->iteration }}</span>
                    <div>
                        <span class="block font-bold text-texto text-lg">{{ $horaFmt }} hrs</span>
                    </div>
                </div>
                <span class="text-[10px] font-bold uppercase text-texto/40 tracking-wider">Pendiente</span>
            </div>
            @endif
            @empty
            <div class="text-center text-gray-500 font-bold text-sm py-4">No tiene turnos asignados para hoy.</div>
            @endforelse
        </div>
    </div>

    <!-- BLOQUE 2: PROGRAMACIÓN SEMANAL -->
    <h2 class="font-bold text-texto text-lg mt-2 px-1">Próximos Días</h2>
    <div class="space-y-3">
        @forelse($turnosFuturos as $fecha => $datos)
        <div class="bg-secundario p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-fondo p-3 rounded-xl mr-4 border border-gray-100 text-center min-w-[50px]">
                    <span class="block text-[10px] text-texto/50 font-bold uppercase">{{ $datos->dia_semana }}</span>
                    <span class="block font-black text-texto text-lg leading-tight">{{ $datos->dia_numero }}</span>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-texto">{{ $datos->vueltas }} Vueltas programadas</h4>
                    <p class="text-xs text-texto/60 font-medium mt-0.5 mb-1">{{ implode(' • ', $datos->horarios) }}</p>
                    <span class="inline-block bg-primario/10 text-primario border border-primario/20 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest">Micro: {{ $datos->patente }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-100 p-4 rounded-2xl border border-gray-200 border-dashed flex justify-center items-center opacity-70">
            <span class="text-xs font-bold text-texto/50 uppercase tracking-widest">Sin asignaciones futuras</span>
        </div>
        @endforelse
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Nómina de Choferes - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">

    <!-- Cabecera -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-extrabold text-texto">Nómina de Choferes</h1>
        </div>
        <a href="/admin/agregar-chofer" class="bg-primario text-secundario px-4 py-2 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-transform flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Agregar
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

    <div class="space-y-4">
        @forelse($choferes as $chofer)

        <!-- Tarjeta de Expediente Rediseñada -->
        <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative transition-all hover:shadow-md">

            <!-- Acento visual superior dinámico según el estado -->
            @php
            $colorEstado = 'bg-gray-400';
            $textoEstado = 'Desvinculado';
            $badgeClase = 'bg-gray-100 text-gray-800';

            if(isset($chofer->estado)) {
            switch($chofer->estado) {
            case 'activo':
            $colorEstado = 'bg-primario';
            $textoEstado = 'Activo';
            $badgeClase = 'bg-green-100 text-green-800';
            break;
            case 'vacaciones':
            $colorEstado = 'bg-blue-500';
            $textoEstado = 'Vacaciones';
            $badgeClase = 'bg-blue-100 text-blue-800';
            break;
            case 'licencia':
            $colorEstado = 'bg-orange-500';
            $textoEstado = 'Licencia Médica';
            $badgeClase = 'bg-orange-100 text-orange-800';
            break;
            }
            } else {
            // Por defecto si no tiene estado registrado
            $colorEstado = 'bg-primario';
            $textoEstado = 'Activo';
            $badgeClase = 'bg-green-100 text-green-800';
            }
            @endphp

            <div class="h-2 w-full {{ $colorEstado }}"></div>

            <div class="p-5">
                <!-- Cabecera de la Tarjeta: Botón Editar -->
                <div class="absolute top-5 right-5">
                    <a href="/admin/editar-chofer/{{ $chofer->id }}" class="text-gray-400 hover:text-primario transition-colors p-1.5 bg-gray-50 rounded-lg flex items-center justify-center" title="Abrir Expediente">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </a>
                </div>

                <!-- Identidad del Conductor -->
                <div class="flex items-center mb-4 pr-10">
                    <div class="bg-gray-50 border border-gray-100 p-3 rounded-full mr-4 flex-shrink-0">
                        <svg class="w-7 h-7 text-texto/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-texto leading-none tracking-tight mb-1">{{ $chofer->nombre }}</h3>
                        <p class="text-[11px] text-texto/50 font-bold uppercase tracking-widest">{{ $chofer->rut ?? 'RUT PENDIENTE' }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100 mt-2">
                    <!-- Estado Laboral -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $badgeClase }}">
                        {{ $textoEstado }}
                    </span>

                    <!-- Botón de Llamada Rápida (Solo visible si hay teléfono) -->
                    @if(!empty($chofer->telefono))
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $chofer->telefono) }}" class="flex items-center text-xs font-black bg-primario/10 text-primario px-4 py-2 rounded-xl active:scale-95 transition-transform shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Llamar
                    </a>
                    @else
                    <span class="text-[10px] font-bold text-texto/30 uppercase tracking-widest">Sin Teléfono</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 px-6 text-center bg-secundario rounded-3xl border border-dashed border-gray-300 shadow-sm flex flex-col items-center">
            <div class="bg-gray-50 p-4 rounded-full mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="font-black text-texto text-lg mb-1">Sin personal registrado</h3>
            <p class="text-sm font-bold text-texto/50">El sistema de recursos humanos está vacío.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
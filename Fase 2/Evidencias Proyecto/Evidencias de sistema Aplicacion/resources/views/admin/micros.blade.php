@extends('layouts.app')
@section('title', 'Gestión de Micros - Admin')
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
            <h1 class="text-xl font-extrabold text-texto">Flota Vehicular</h1>
        </div>
        <a href="/admin/agregar-micro" class="bg-primario text-secundario px-4 py-2 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-transform flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Agregar
        </a>
    </div>

    <!-- Mensaje de confirmación del sistema -->
    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="block font-bold text-sm">{{ session('exito') }}</span>
    </div>
    @endif

    <!-- Listado de Flota -->
    <div class="space-y-5">
        @forelse($vehiculos as $vehiculo)

        <!-- Tarjeta de Micro Rediseñada -->
        <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative transition-all hover:shadow-md">

            <!-- Acento visual superior dinámico según el estado -->
            <div class="h-2 w-full {{ $vehiculo->estado === 'operativo' ? 'bg-acento' : ($vehiculo->estado === 'en_mantencion' ? 'bg-peligro' : 'bg-gray-400') }}"></div>

            <div class="p-5">
                <!-- Cabecera de la Tarjeta: Estado y Botón Editar -->
                <div class="flex justify-between items-start mb-4">
                    @if($vehiculo->estado === 'operativo')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-2"></span> Operativa
                    </span>
                    @elseif($vehiculo->estado === 'en_mantencion')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-800 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-2"></span> En Taller
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-600 mr-2"></span> Inactiva
                    </span>
                    @endif

                    <a href="/admin/editar-micro/{{ $vehiculo->id }}" class="text-gray-400 hover:text-primario transition-colors p-1 bg-gray-50 rounded-lg" title="Editar Máquina">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </a>
                </div>

                <!-- Cuerpo Central: Patente y Datos -->
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-texto/50 tracking-widest mb-1">Patente</p>
                        <h3 class="text-3xl font-black text-texto tracking-tight leading-none">{{ $vehiculo->patente }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] uppercase font-bold text-texto/50 tracking-widest mb-1">Capacidad</p>
                        <p class="text-xl font-black text-texto leading-none">{{ $vehiculo->capacidad }} <span class="text-sm font-bold text-texto/50">as.</span></p>
                    </div>
                </div>

                <!-- Información Adicional -->
                <p class="text-xs text-texto/60 font-bold mb-2">Año de Registro: {{ $vehiculo->anio ?? 'No especificado' }}</p>

                <!-- Botón de acción rápida (Habilitar) -->
                @if($vehiculo->estado === 'en_mantencion')
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <form action="{{ route('admin.micros.estado', $vehiculo->id) }}" method="POST" onsubmit="return confirm('¿Confirma que la máquina {{ $vehiculo->patente }} ha sido reparada y está lista para operar?');">
                        @csrf
                        <input type="hidden" name="estado" value="operativo">
                        <button type="submit" class="w-full bg-texto text-secundario py-3 rounded-xl text-sm font-bold flex justify-center items-center space-x-2 active:scale-95 transition-transform hover:bg-gray-800 shadow-sm">
                            <svg class="w-5 h-5 text-acento" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Habilitar para Ruta</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty

        <!-- Estado Vacío Mejorado -->
        <div class="py-12 px-6 text-center bg-secundario rounded-3xl border border-dashed border-gray-300 shadow-sm flex flex-col items-center">
            <div class="bg-gray-50 p-4 rounded-full mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17l.867 12.142A2 2 0 0117.88 31H6.12a2 2 0 01-1.987-1.858L5 17m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="font-black text-texto text-lg mb-1">Sin máquinas registradas</h3>
            <p class="text-sm font-bold text-texto/50">Agregue su primer vehículo para comenzar a operar la flota.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
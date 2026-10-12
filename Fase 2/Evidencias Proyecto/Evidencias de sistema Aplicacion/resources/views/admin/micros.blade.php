@extends('layouts.app')
@section('title', 'Gestión de Micros - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
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
            + Agregar
        </a>
    </div>

    <!-- Mensaje de confirmación del sistema -->
    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative text-center shadow-sm">
        <span class="block font-bold text-sm">{{ session('exito') }}</span>
    </div>
    @endif

    <div class="space-y-4">
        @forelse($vehiculos as $vehiculo)
        <!-- Tarjeta de Micro Registrada (Interactiva) -->
        <div class="block bg-secundario p-5 rounded-2xl shadow-sm border {{ $vehiculo->estado === 'en_mantencion' ? 'border-peligro/30 bg-red-50/20' : 'border-gray-100' }} hover:border-primario/50 transition-all flex flex-col space-y-3 relative">

            <!-- Ícono indicador de edición (Abre la vista completa de edición) -->
            <a href="/admin/editar-micro/{{ $vehiculo->id }}" class="absolute top-4 right-4 bg-gray-100 p-1.5 rounded-lg text-texto/40 hover:text-primario transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </a>

            <div class="flex justify-between items-start border-b border-gray-50 pb-3 pr-10">
                <div>
                    <h3 class="font-black text-lg text-texto tracking-wide">{{ $vehiculo->patente }}</h3>
                    <p class="text-xs text-texto/60 font-bold">Máquina {{ $vehiculo->anio ?? 'Registrada' }}</p>
                </div>

                <!-- Lógica condicional para el color del estado -->
                @if($vehiculo->estado === 'operativo')
                <span class="bg-acento/10 text-acento px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">Operativa</span>
                @elseif($vehiculo->estado === 'en_mantencion')
                <span class="bg-peligro/10 text-peligro px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse">En Pana</span>
                @else
                <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">Inactiva</span>
                @endif
            </div>

            <div class="flex justify-between items-center text-sm text-texto/80 font-medium">
                <span>Capacidad: {{ $vehiculo->capacidad }} as.</span>

                <!-- Botón de acción rápida para rehabilitar la máquina -->
                @if($vehiculo->estado === 'en_mantencion')
                <form action="{{ route('admin.micros.estado', $vehiculo->id) }}" method="POST" onsubmit="return confirm('¿Confirma que esta máquina ha sido reparada y está lista para operar?');">
                    @csrf
                    <input type="hidden" name="estado" value="operativo">
                    <button type="submit" class="text-[10px] font-black bg-primario/10 text-primario px-3 py-1.5 rounded-lg uppercase active:scale-95 transition-transform flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Habilitar
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500 font-bold bg-secundario rounded-2xl border border-gray-100 shadow-sm">
            No hay máquinas registradas en el sistema.
        </div>
        @endforelse
    </div>
</div>
@endsection
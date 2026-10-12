@extends('layouts.app')
@section('title', 'Ver Jornadas - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-extrabold text-texto">Jornadas Programadas</h1>
            <p class="text-texto/70 text-xs">Directorio de salidas futuras</p>
        </div>
    </div>

    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
        <span class="block sm:inline font-bold text-sm">{{ session('exito') }}</span>
    </div>
    @endif

    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($turnosProgramados as $turno)
            <div class="p-5 flex flex-col space-y-3 hover:bg-fondo transition-colors">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-primario/10 border border-primario/20 p-3 rounded-xl mr-3">
                            <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-black text-texto text-sm capitalize">{{ $turno->dia }}</p>
                            <p class="text-lg font-black text-primario">{{ $turno->hora }} hrs</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm bg-gray-200 text-gray-700">
                            {{ $turno->patente }}
                        </span>
                        <p class="text-xs text-texto/60 font-medium mt-1">{{ $turno->conductor }}</p>
                    </div>
                </div>

                <!-- Botón para eliminar/cancelar -->
                <div class="flex justify-end pt-2">
                    <form action="{{ route('admin.turno.destroy', $turno->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea cancelar este turno programado?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center text-xs font-bold text-peligro bg-peligro/10 px-3 py-1.5 rounded-lg active:scale-95 transition-transform">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Cancelar Turno
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500 font-bold">
                No hay jornadas programadas en el sistema.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
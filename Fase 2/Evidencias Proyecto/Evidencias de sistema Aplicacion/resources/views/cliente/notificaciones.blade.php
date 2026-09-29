@extends('layouts.app')
@section('title', 'Notificaciones - Micros Culiprán')
@section('content')
<div class="flex flex-col pb-6">
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Notificaciones</h1>
    </div>

    <div class="space-y-3">
        <!-- Mensaje No Leído (Alerta Crítica) -->
        <a href="/notificaciones/detalle" class="block bg-peligro/5 p-4 rounded-2xl border border-peligro/20 active:bg-peligro/10 transition-colors relative">
            <span class="absolute top-4 right-4 h-2.5 w-2.5 rounded-full bg-peligro"></span>
            <div class="flex items-start mr-4">
                <div class="bg-peligro/10 p-2 rounded-lg mr-3 shrink-0">
                    <svg class="w-6 h-6 text-peligro" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-texto text-sm leading-tight mb-1">Micro AB-1234 en Pana</h3>
                    <p class="text-xs text-texto/60 line-clamp-2">Estimados pasajeros, la máquina AB-1234 ha sufrido un desperfecto en San Manuel. Enviaremos unidad de respaldo.</p>
                    <span class="text-[10px] font-bold text-peligro mt-2 block uppercase">Hace 5 minutos</span>
                </div>
            </div>
        </a>

        <!-- Mensaje Leído (Informativo) -->
        <a href="/notificaciones/detalle" class="block bg-secundario p-4 rounded-2xl border border-gray-100 shadow-sm active:bg-fondo transition-colors">
            <div class="flex items-start">
                <div class="bg-primario/10 p-2 rounded-lg mr-3 shrink-0">
                    <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-texto text-sm leading-tight mb-1">Actualización de Tarifas</h3>
                    <p class="text-xs text-texto/60 line-clamp-2">A partir del próximo lunes, la tarifa de estudiante será actualizada a $400 pesos.</p>
                    <span class="text-[10px] font-bold text-texto/40 mt-2 block uppercase">Ayer, 18:30 hrs</span>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
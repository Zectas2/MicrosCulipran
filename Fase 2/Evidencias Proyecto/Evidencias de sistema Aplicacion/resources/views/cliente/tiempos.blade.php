@extends('layouts.app')
@section('title', 'Tiempos Promedio - Micros Culiprán')
@section('content')
<div class="flex flex-col">
    <!-- Cabecera de Navegación -->
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Tiempos Estimados</h1>
    </div>

    <!-- Desglose por Tramos -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100 mb-6">
        <p class="text-xs text-texto/60 text-center mb-5 uppercase tracking-wide font-bold">Sujeto a condiciones de tránsito</p>
        
        <div class="space-y-5">
            <!-- Tramo 1 -->
            <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-primario uppercase tracking-wider mb-1">Tramo Inicial</span>
                    <span class="font-bold text-texto text-sm">Terminal &rarr; Cruce Arañas</span>
                </div>
                <div class="bg-fondo px-3 py-2 rounded-xl border border-gray-200 text-center min-w-[70px]">
                    <span class="block text-xl font-black text-texto">10</span>
                    <span class="block text-[9px] text-texto/50 uppercase font-bold">Min</span>
                </div>
            </div>
            
            <!-- Tramo 2 -->
            <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-acento uppercase tracking-wider mb-1">Tramo Medio</span>
                    <span class="font-bold text-texto text-sm">Cruce &rarr; San Manuel</span>
                </div>
                <div class="bg-fondo px-3 py-2 rounded-xl border border-gray-200 text-center min-w-[70px]">
                    <span class="block text-xl font-black text-texto">15</span>
                    <span class="block text-[9px] text-texto/50 uppercase font-bold">Min</span>
                </div>
            </div>

            <!-- Tramo 3 -->
            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-primario uppercase tracking-wider mb-1">Tramo Final</span>
                    <span class="font-bold text-texto text-sm">San Manuel &rarr; Melipilla</span>
                </div>
                <div class="bg-fondo px-3 py-2 rounded-xl border border-gray-200 text-center min-w-[70px]">
                    <span class="block text-xl font-black text-texto">20</span>
                    <span class="block text-[9px] text-texto/50 uppercase font-bold">Min</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Totalizador de Viaje -->
    <div class="bg-primario rounded-3xl p-6 shadow-md flex justify-between items-center text-secundario border-b-4 border-orange-700">
        <div>
            <h3 class="font-black text-lg">Recorrido Completo</h3>
            <p class="text-xs text-secundario/90 mt-1 font-medium">Culiprán a Melipilla</p>
        </div>
        <div class="text-right">
            <span class="block text-4xl font-extrabold leading-none">45</span>
            <span class="block text-[10px] uppercase font-bold tracking-widest mt-1 opacity-90">Minutos</span>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Mapa en Vivo - Micros Culiprán')
@section('content')
<div class="flex flex-col h-[calc(100vh-140px)]">
    <!-- Cabecera con botón de retroceso -->
    <div class="flex items-center mb-4">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Mapa en Vivo</h1>
    </div>

    <!-- Contenedor del Mapa -->
    <div class="relative flex-grow bg-gray-300 rounded-2xl overflow-hidden shadow-inner border border-gray-300">
        <!-- Marcador temporal visual -->
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-texto/50 font-bold bg-secundario/80 px-4 py-2 rounded-lg">[Integración de API de Google Maps / Mapbox aquí]</span>
        </div>
        
        <!-- Tarjeta Flotante Inferior -->
        <div class="absolute bottom-4 left-4 right-4 bg-secundario rounded-xl shadow-lg p-4 border border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="bg-acento w-3 h-3 rounded-full animate-pulse mr-2"></span>
                    <p class="font-bold text-texto text-sm">2 Micros en ruta</p>
                </div>
                <span class="text-xs font-bold text-primario bg-primario/10 px-2 py-1 rounded-md">Actualizado ahora</span>
            </div>
        </div>
    </div>
</div>
@endsection
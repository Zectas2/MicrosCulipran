@extends('layouts.app')
@section('title', 'Inicio - Micros Culiprán')

@section('content')
<div class="flex flex-col">

    <!-- Hero Section (Bienvenida) -->
    <div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -mt-6 pb-20 pt-8 px-6 overflow-hidden bg-gradient-to-b from-orange-100 to-fondo">
        <div class="relative z-10 w-2/3">
            <h1 class="text-3xl font-extrabold text-texto leading-tight">
                ¡Bienvenido a<br><span class="text-primario">Micros Culiprán!</span>
            </h1>
            <p class="text-texto/80 mt-2 font-medium text-sm">Tu transporte, más cerca de ti.</p>
        </div>
        <!-- SVG Decorativo de Micro -->
        <div class="absolute right-[-2rem] bottom-4 opacity-50 z-0">
            <svg class="w-48 h-48 text-primario" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 16c0 1.1.9 2 2 2h1v2c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-2h8v2c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-2h1c1.1 0 2-.9 2-2V8c0-2.21-1.79-4-4-4H8C5.79 4 4 5.79 4 8v8zm3 0c-.83 0-1.5-.67-1.5-1.5S6.17 13 7 13s1.5.67 1.5 1.5S7.83 16 7 16zm10 0c-.83 0-1.5-.67-1.5-1.5S16.17 13 17 13s1.5.67 1.5 1.5S17.83 16 17 16zM6 10V8h12v2H6z" />
            </svg>
        </div>
    </div>

    <!-- Reemplazo del Buscador: Tarjeta de Estado de Servicio -->
    <div class="relative -mt-14 bg-secundario rounded-2xl shadow-lg p-5 border border-gray-100 z-20">
        <div class="flex justify-between items-center mb-3">
            <div class="flex items-center text-texto font-bold">
                <svg class="w-5 h-5 text-primario mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h2>Estado del Servicio</h2>
            </div>
            <!-- Etiqueta dinámica de estado -->
            <span class="bg-acento/10 text-acento border border-acento/20 px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider">Operativo</span>
        </div>

        <div class="bg-fondo rounded-xl p-3 border border-gray-200 flex justify-between items-center">
            <div>
                <p class="text-[10px] text-texto/60 font-bold uppercase tracking-wide">Ruta Única</p>
                <p class="text-sm font-black text-texto">Melipilla &rarr; Culiprán</p>
            </div>
            <div class="text-right border-l border-gray-300 pl-3">
                <p class="text-[10px] text-texto/60 font-bold uppercase tracking-wide">Próxima Micro</p>
                <p class="text-sm font-black text-primario">En 12 min</p>
            </div>
        </div>
    </div>

    <!-- CTA Principal (Ver Mapa en Vivo) -->
    <a href="/mapa" class="w-full mt-6 bg-primario text-secundario rounded-xl p-4 flex items-center justify-between shadow-md active:scale-95 transition-transform">
        <div class="flex items-center">
            <div class="bg-secundario/20 p-2 rounded-lg mr-3">
                <svg class="w-6 h-6 text-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="text-left">
                <h3 class="font-bold text-lg leading-tight">Ver mapa en vivo</h3>
                <p class="text-secundario/90 text-xs mt-0.5">Sigue nuestras micros en tiempo real</p>
            </div>
        </div>
        <svg class="w-6 h-6 text-secundario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>

    <!-- Grid de Accesos Rápidos -->
    <div class="grid grid-cols-2 gap-4 mt-6">
        <a href="/horarios" class="bg-secundario p-4 rounded-xl shadow-sm border border-gray-100 text-left active:bg-fondo transition-colors flex flex-col items-start">
            <svg class="w-7 h-7 text-primario mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h4 class="font-bold text-texto text-sm">Horarios</h4>
            <p class="text-[10px] text-texto/60 mt-1 leading-tight">Revisa los horarios de nuestras rutas</p>
        </a>

        <a href="/rutas" class="bg-secundario p-4 rounded-xl shadow-sm border border-gray-100 text-left active:bg-fondo transition-colors flex flex-col items-start">
            <svg class="w-7 h-7 text-primario mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <h4 class="font-bold text-texto text-sm">Rutas</h4>
            <p class="text-[10px] text-texto/60 mt-1 leading-tight">Conoce nuestras rutas y recorridos</p>
        </a>

        <a href="/tarifas" class="bg-secundario p-4 rounded-xl shadow-sm border border-gray-100 text-left active:bg-fondo transition-colors flex flex-col items-start">
            <svg class="w-7 h-7 text-primario mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h4 class="font-bold text-texto text-sm">Tarifas</h4>
            <p class="text-[10px] text-texto/60 mt-1 leading-tight">Información sobre tarifas y medios de pago</p>
        </a>

        <a href="/tiempos" class="bg-secundario p-4 rounded-xl shadow-sm border border-gray-100 text-left active:bg-fondo transition-colors flex flex-col items-start">
            <svg class="w-7 h-7 text-primario mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h4 class="font-bold text-texto text-sm">Tiempos promedio</h4>
            <p class="text-[10px] text-texto/60 mt-1 leading-tight">Revisa los tiempos de viaje estimados</p>
        </a>
    </div>

    <!-- Tarjeta de Información Importante -->
    <div class="mt-6 bg-acento/10 rounded-xl p-4 flex items-start border border-acento/30">
        <svg class="w-6 h-6 text-acento shrink-0 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h4 class="font-bold text-texto text-sm">Información importante</h4>
            <p class="text-xs text-texto/80 mt-1 leading-relaxed">Mantente informado sobre cambios de horarios y novedades aquí.</p>
        </div>
    </div>

</div>
@endsection
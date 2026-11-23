@extends('layouts.app')
@section('title', 'Tiempos Promedio - Micros Culiprán')
@section('content')
<div class="flex flex-col space-y-6">

    <!-- Cabecera de Navegación -->
    <div class="flex items-center">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-extrabold text-texto leading-tight">Indicadores de Ruta</h1>
            <p class="text-[10px] font-bold text-texto/50 uppercase tracking-widest mt-0.5">Basado en datos reales</p>
        </div>
    </div>

    <!-- Tarjeta Principal: Duración del Viaje -->
    <div class="bg-primario rounded-3xl p-6 shadow-md relative overflow-hidden flex flex-col justify-center min-h-[150px]">
        <!-- Patrón Decorativo de Fondo -->
        <div class="absolute -right-6 -bottom-6 opacity-10">
            <svg class="w-40 h-40 text-secundario" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
            </svg>
        </div>

        <div class="relative z-10 flex justify-between items-end">
            <div class="pr-4">
                <span class="inline-block bg-secundario/20 text-secundario text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg mb-3 shadow-inner">
                    Ruta Completa
                </span>
                <h2 class="text-secundario font-black text-[22px] leading-tight mb-1">Culiprán &rarr; Melipilla</h2>
                <p class="text-secundario/80 text-[11px] font-bold uppercase tracking-wider">Duración promedio del trayecto</p>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="block text-6xl font-black text-secundario leading-none tracking-tighter">{{ $duracionPromedio }}</span>
                <span class="block text-[11px] uppercase font-black tracking-widest mt-1 text-secundario/90">Minutos</span>
            </div>
        </div>
    </div>

    <!-- Grid de Indicadores Secundarios -->
    <div class="grid grid-cols-2 gap-4">

        <!-- Frecuencia -->
        <div class="bg-secundario rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between hover:border-acento/50 transition-colors">
            <div class="bg-acento/10 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-acento" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <span class="block text-2xl font-black text-texto">{{ $frecuencia }} <span class="text-sm font-bold text-texto/50">min</span></span>
                <h3 class="text-[10px] font-bold text-texto/80 uppercase tracking-widest mt-1">Frecuencia Aprox.</h3>
            </div>
        </div>

        <!-- Horario -->
        <div class="bg-secundario rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between hover:border-primario/50 transition-colors">
            <div class="bg-primario/10 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <span class="block text-lg font-black text-texto tracking-tight">{{ $horaInicio }} - {{ $horaFin }}</span>
                <h3 class="text-[10px] font-bold text-texto/80 uppercase tracking-widest mt-1">Horario de Hoy</h3>
            </div>
        </div>

    </div>

    <!-- Nota Aclaratoria Técnica -->
    <div class="bg-blue-50/50 border border-blue-100/70 rounded-2xl p-4 flex items-start mt-2">
        <div class="bg-blue-100/80 p-2 rounded-xl mr-3 flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-[11px] text-blue-800/80 font-bold leading-relaxed uppercase tracking-wider mb-1">Transparencia Operativa</p>
            <p class="text-xs text-blue-900/70 font-medium leading-relaxed">
                Estos valores son calculados matemáticamente por nuestro sistema basándose en el historial de las máquinas durante los últimos 7 días. El tiempo puede variar según el tráfico vehicular.
            </p>
        </div>
    </div>

</div>
@endsection
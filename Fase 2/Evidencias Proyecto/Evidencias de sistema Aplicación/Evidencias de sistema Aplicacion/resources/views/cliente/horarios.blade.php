@extends('layouts.app')
@section('title', 'Horarios - Micros Culiprán')
@section('content')
<div class="flex flex-col pb-6">
    <!-- Cabecera de Navegación -->
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Horarios de Servicio</h1>
    </div>

    <div class="space-y-5">
        <!-- Bloque Principal de Horarios -->
        <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-primario px-5 py-4 flex justify-between items-center">
                <h3 class="text-secundario font-black tracking-wide uppercase text-sm">Salidas de Hoy</h3>
                <span class="text-secundario/80 text-[10px] font-bold uppercase tracking-wider">{{ $fechaHoy }}</span>
            </div>

            <div class="p-2">
                @if($hayDatos)
                <!-- Grilla de horarios de hoy -->
                <div class="grid grid-cols-3 gap-2 p-3">
                    @foreach($horariosSalida as $hora)
                    <div class="bg-fondo border border-gray-100 rounded-xl py-3 flex flex-col items-center justify-center relative overflow-hidden">
                        <div class="absolute top-0 w-full h-1 bg-acento/20"></div>

                        <span class="font-black text-texto text-lg tracking-wider">{{ $hora }}</span>
                        <span class="text-[9px] font-bold text-texto/40 uppercase tracking-widest mt-0.5">hrs</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 px-4">
                    <div class="bg-fondo w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-texto/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-texto/60 text-sm font-bold block">No hay recorridos programados para el día de hoy.</span>
                    <span class="text-texto/40 text-xs block mt-1">Por favor, revise más tarde.</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Nota Informativa -->
        <div class="text-[10px] text-texto/40 font-bold uppercase tracking-wider text-center px-4 mt-2">
            Los horarios reflejan las salidas desde el terminal base y están sujetos a las condiciones de tránsito.
        </div>
    </div>
</div>
@endsection
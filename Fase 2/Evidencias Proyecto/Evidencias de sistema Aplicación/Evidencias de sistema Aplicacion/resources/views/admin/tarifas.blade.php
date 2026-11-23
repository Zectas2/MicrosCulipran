@extends('layouts.app')
@section('title', 'Configuración de Tarifas - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-8">

    <!-- Cabecera Ejecutiva -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin" class="bg-gray-100 p-2.5 rounded-full text-texto/60 hover:text-primario hover:bg-primario/10 transition-colors mr-4" title="Volver al Panel">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-texto leading-none">Cuadro Tarifario</h1>
                <p class="text-xs font-bold text-texto/50 uppercase tracking-widest mt-1">Gestión de Precios Comerciales</p>
            </div>
        </div>
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

    <form method="POST" action="{{ route('admin.tarifas.update') }}" class="space-y-6">
        @csrf

        <!-- SECCIÓN 1: Tarifas Dinámicas por Tramo -->
        <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
            <div class="h-1.5 w-full bg-primario"></div>

            <div class="p-6">
                <div class="flex items-center border-b border-gray-100 pb-3 mb-5">
                    <div class="bg-primario/10 p-2.5 rounded-xl mr-3">
                        <svg class="w-5 h-5 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-texto uppercase tracking-wider">Tarifas por Tramo (Adulto)</h2>
                        <p class="text-[10px] font-bold text-texto/50 uppercase">Ruta Variante San José / Melipilla</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @forelse($tarifasGenerales as $tarifa)
                    <div class="bg-fondo border border-gray-200 rounded-2xl p-4 transition-all hover:border-primario/30">
                        <label class="flex flex-col text-[10px] font-bold text-texto/60 uppercase tracking-widest mb-3">
                            <span class="text-texto font-black text-xs mb-1">Origen: {{ $tarifa->punto_origen }}</span>
                            <span class="flex items-center text-primario">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                                Destino: {{ $tarifa->punto_destino }}
                            </span>
                        </label>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-texto/40 font-black text-lg">$</span>
                            </div>
                            <input type="number" name="tarifas[{{ $tarifa->id }}]" value="{{ $tarifa->precio }}" required class="w-full bg-white border-2 border-gray-100 rounded-xl py-3 pl-10 pr-4 text-texto focus:outline-none focus:border-primario font-black text-xl transition-all shadow-inner">
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-gray-500 font-bold bg-white rounded-2xl border border-dashed border-gray-300">
                        No hay tramos configurados en la base de datos.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: Tarifas Universales Planas -->
        <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
            <div class="h-1.5 w-full bg-acento"></div>

            <div class="p-6">
                <div class="flex items-center border-b border-gray-100 pb-3 mb-5">
                    <div class="bg-acento/10 p-2.5 rounded-xl mr-3">
                        <svg class="w-5 h-5 text-acento" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-texto uppercase tracking-wider">Tarifas Preferenciales</h2>
                        <p class="text-[10px] font-bold text-texto/50 uppercase">Valor único garantizado en toda la ruta</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @forelse($tarifasEspeciales as $tarifa)
                    <div class="bg-fondo border border-gray-200 rounded-2xl p-4 transition-all hover:border-acento/50">
                        <label class="flex flex-col text-[10px] font-bold text-texto/60 uppercase tracking-widest mb-3">
                            <span class="text-acento font-black text-xs mb-1">Pasaje Diferenciado</span>
                            <span>Categoría: {{ $tarifa->tipo_tarifa }}</span>
                        </label>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-acento/50 font-black text-lg">$</span>
                            </div>
                            <input type="number" name="tarifas[{{ $tarifa->id }}]" value="{{ $tarifa->precio }}" required class="w-full bg-white border-2 border-acento/20 rounded-xl py-3 pl-10 pr-4 text-texto focus:outline-none focus:border-acento focus:ring-2 focus:ring-acento/20 font-black text-xl transition-all shadow-inner">
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-gray-500 font-bold bg-white rounded-2xl border border-dashed border-gray-300">
                        No hay tarifas preferenciales configuradas.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Botón de Guardado Global -->
        <div class="pt-4">
            <button type="submit" class="w-full bg-primario text-secundario font-black py-5 rounded-2xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-3 hover:bg-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                <span class="text-lg">Aplicar Cuadro Tarifario</span>
            </button>
        </div>
    </form>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Ajustar Tarifas - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Configuración de Tarifas</h1>
    </div>

    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm font-bold text-sm text-center">
        {{ session('exito') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.tarifas.update') }}" class="space-y-6">
        @csrf

        <!-- SECCIÓN 1: Tarifas Dinámicas por Tramo -->
        <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-sm font-black text-texto mb-4 uppercase tracking-wider border-b border-gray-100 pb-2">Tarifas Generales (Por Tramo)</h2>
            <div class="space-y-4">
                @foreach($tarifasGenerales as $tarifa)
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label class="block text-xs font-bold text-texto/80 uppercase tracking-wider">
                            {{ $tarifa->punto_origen }} &rarr; {{ $tarifa->punto_destino }}
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 font-bold text-texto/50">$</span>
                        <input type="number" name="tarifas[{{ $tarifa->id }}]" value="{{ $tarifa->precio }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-8 pr-4 text-texto focus:outline-none focus:border-primario font-black text-lg">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SECCIÓN 2: Tarifas Universales Planas -->
        <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-sm font-black text-acento mb-4 uppercase tracking-wider border-b border-gray-100 pb-2">Tarifas Especiales (Todo el recorrido)</h2>
            <div class="space-y-4">
                @foreach($tarifasEspeciales as $tarifa)
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label class="block text-xs font-bold text-texto/80 uppercase tracking-wider text-acento">
                            Pasaje {{ $tarifa->tipo_tarifa }}
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 font-bold text-texto/50">$</span>
                        <input type="number" name="tarifas[{{ $tarifa->id }}]" value="{{ $tarifa->precio }}" required class="w-full bg-fondo border border-acento/30 rounded-xl py-3 pl-8 pr-4 text-texto focus:outline-none focus:border-acento font-black text-lg">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Botón de Guardado Global -->
        <div>
            <button type="submit" class="w-full bg-primario text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform">
                Guardar Todas las Tarifas
            </button>
        </div>
    </form>
</div>
@endsection
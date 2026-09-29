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

    <div class="space-y-4">
        <!-- Tarjeta de Micro Registrada (Interactiva) -->
        <a href="/admin/editar-micro" class="block bg-secundario p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-primario/50 active:bg-fondo transition-all flex flex-col space-y-3 relative">
            <!-- Ícono indicador de edición -->
            <div class="absolute top-4 right-4 bg-gray-100 p-1.5 rounded-lg text-texto/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </div>

            <div class="flex justify-between items-start border-b border-gray-50 pb-3">
                <div>
                    <h3 class="font-black text-lg text-texto tracking-wide">AB-1234</h3>
                    <p class="text-xs text-texto/60 font-bold">Mercedes-Benz Sprinter</p>
                </div>
                <span class="bg-acento/10 text-acento px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider mr-8">Activa</span>
            </div>
            <div class="flex justify-between text-sm text-texto/80 font-medium">
                <span>Año: 2022</span>
                <span>Capacidad: 30 as.</span>
            </div>
        </a>
        <!-- Se iterarán dinámicamente desde la base de datos -->
    </div>
</div>
@endsection
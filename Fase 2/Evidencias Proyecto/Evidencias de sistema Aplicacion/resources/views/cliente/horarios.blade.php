@extends('layouts.app')
@section('title', 'Horarios - Micros Culiprán')
@section('content')
<div class="flex flex-col">
    <!-- Cabecera de Navegación -->
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Horarios de Servicio</h1>
    </div>

    <div class="space-y-5">
        <!-- Bloque de Lunes a Viernes -->
        <div class="bg-secundario rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-primario px-4 py-3">
                <h3 class="text-secundario font-bold">Lunes a Viernes</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-texto/70 text-sm font-medium">Primer recorrido</span>
                    <span class="font-bold text-texto">06:00 hrs</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-texto/70 text-sm font-medium">Frecuencia estimada</span>
                    <span class="font-bold text-acento">Cada 15 min</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-texto/70 text-sm font-medium">Último recorrido</span>
                    <span class="font-bold text-texto">21:30 hrs</span>
                </div>
            </div>
        </div>

        <!-- Bloque de Fines de Semana -->
        <div class="bg-secundario rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-acento px-4 py-3">
                <h3 class="text-secundario font-bold">Sábados, Domingos y Festivos</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-texto/70 text-sm font-medium">Primer recorrido</span>
                    <span class="font-bold text-texto">07:00 hrs</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-texto/70 text-sm font-medium">Frecuencia estimada</span>
                    <span class="font-bold text-texto">Cada 30 min</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-texto/70 text-sm font-medium">Último recorrido</span>
                    <span class="font-bold text-texto">20:00 hrs</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
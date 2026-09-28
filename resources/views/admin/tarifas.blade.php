@extends('layouts.app')
@section('title', 'Ajustar Tarifas - Admin')
@section('content')
<div class="flex flex-col space-y-6">
    <div class="flex items-center mb-2">
        <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Configuración de Tarifas</h1>
    </div>

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Pasaje General (Tramo Completo)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 font-bold text-texto/50">$</span>
                    <input type="number" value="1200" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-8 pr-4 text-texto focus:outline-none focus:border-primario font-black text-lg">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Pasaje Local (Tramos Cortos)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 font-bold text-texto/50">$</span>
                    <input type="number" value="600" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-8 pr-4 text-texto focus:outline-none focus:border-primario font-black text-lg">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Tarifa Estudiante (TNE)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 font-bold text-texto/50">$</span>
                    <input type="number" value="400" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-8 pr-4 text-texto focus:outline-none focus:border-primario font-black text-lg">
                </div>
            </div>
            <div class="pt-2">
                <button type="button" class="w-full bg-acento text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform">
                    Actualizar Tarifas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
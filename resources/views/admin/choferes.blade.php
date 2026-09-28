@extends('layouts.app')
@section('title', 'Gestión de Choferes - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-extrabold text-texto">Nómina de Choferes</h1>
        </div>
        <a href="/admin/agregar-chofer" class="bg-primario text-secundario px-4 py-2 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-transform flex items-center">
            + Agregar
        </a>
    </div>

    <div class="space-y-4">
        <!-- Tarjeta de Chofer Registrado (Interactiva) -->
        <a href="/admin/editar-chofer" class="block bg-secundario p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-primario/50 active:bg-fondo transition-all flex flex-col space-y-3 relative">
            <!-- Ícono indicador de edición -->
            <div class="absolute top-4 right-4 bg-gray-100 p-1.5 rounded-lg text-texto/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </div>

            <div class="flex justify-between items-start border-b border-gray-50 pb-3">
                <div class="flex items-center">
                    <div class="bg-fondo p-3 rounded-full mr-3">
                        <svg class="w-6 h-6 text-texto/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-texto tracking-wide pr-8">Juan Pérez González</h3>
                        <p class="text-xs text-texto/60 font-bold">RUT: 12.345.678-9</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between text-sm text-texto/80 font-medium">
                <span class="bg-acento/10 text-acento px-2 py-0.5 rounded text-[10px] font-bold uppercase">Activo</span>
                <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg> +56 9 1234 5678</span>
            </div>
        </a>
        <!-- Iteración dinámica pendiente de base de datos -->
    </div>
</div>
@endsection
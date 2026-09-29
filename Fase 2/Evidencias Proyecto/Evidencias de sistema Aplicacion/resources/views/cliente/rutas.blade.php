@extends('layouts.app')
@section('title', 'Ruta - Micros Culiprán')
@section('content')
<div class="flex flex-col">
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Recorrido Único</h1>
    </div>

    <div class="bg-secundario p-6 rounded-3xl shadow-sm border border-gray-100">
        <div class="relative border-l-2 border-primario/30 ml-3 space-y-8">
            
            <div class="relative">
                <span class="absolute -left-[37px] bg-primario text-secundario w-6 h-6 rounded-full border-4 border-secundario flex items-center justify-center"></span>
                <h3 class="font-bold text-texto">Terminal Culiprán</h3>
                <p class="text-xs text-texto/60 mt-1">Punto de salida</p>
            </div>

            <div class="relative">
                <span class="absolute -left-[37px] bg-secundario w-6 h-6 rounded-full border-4 border-primario/50"></span>
                <h3 class="font-bold text-texto">Cruce Las Arañas</h3>
            </div>

            <div class="relative">
                <span class="absolute -left-[37px] bg-secundario w-6 h-6 rounded-full border-4 border-primario/50"></span>
                <h3 class="font-bold text-texto">Sector San Manuel</h3>
            </div>

            <div class="relative">
                <span class="absolute -left-[37px] bg-texto text-secundario w-6 h-6 rounded-full border-4 border-secundario flex items-center justify-center"></span>
                <h3 class="font-bold text-texto">Centro de Melipilla</h3>
                <p class="text-xs text-texto/60 mt-1">Destino final (Terminal local)</p>
            </div>

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Tarifas - Micros Culiprán')
@section('content')
<div class="flex flex-col">
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Tarifas Oficiales</h1>
    </div>

    <div class="space-y-4">
        <!-- Tarjeta General -->
        <div class="bg-secundario p-5 rounded-2xl shadow-sm border-l-4 border-primario">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-texto text-lg">Pasaje General</h3>
                    <p class="text-xs text-texto/60">Recorrido completo</p>
                </div>
                <span class="text-2xl font-black text-primario">$1.200</span>
            </div>
        </div>

        <!-- Tarjeta Local -->
        <div class="bg-secundario p-5 rounded-2xl shadow-sm border-l-4 border-acento">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-texto text-lg">Pasaje Local</h3>
                    <p class="text-xs text-texto/60">Tramos cortos intermedios</p>
                </div>
                <span class="text-2xl font-black text-acento">$600</span>
            </div>
        </div>

        <!-- Tarjeta Estudiante -->
        <div class="bg-secundario p-5 rounded-2xl shadow-sm border-l-4 border-gray-400">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-texto text-lg">Estudiante</h3>
                    <p class="text-xs text-texto/60">Con TNE vigente</p>
                </div>
                <span class="text-2xl font-black text-texto/80">$400</span>
            </div>
        </div>
    </div>
    
    <div class="mt-8 bg-acento/10 rounded-xl p-4 flex items-start">
        <svg class="w-5 h-5 text-acento shrink-0 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="text-xs text-texto/80">Solo se acepta pago en efectivo al momento de abordar la máquina.</p>
    </div>
</div>
@endsection
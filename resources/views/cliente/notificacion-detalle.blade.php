@extends('layouts.app')
@section('title', 'Comunicado Oficial - Micros Culiprán')
@section('content')
<div class="flex flex-col h-full">
    <div class="flex items-center mb-6">
        <a href="/notificaciones" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Comunicado Oficial</h1>
    </div>

    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Cabecera de la Notificación según su tipo (Ej: Peligro) -->
        <div class="bg-peligro px-5 py-4 flex items-center space-x-3">
            <div class="bg-secundario/20 p-2 rounded-xl text-secundario">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-secundario font-black text-lg leading-tight">Micro AB-1234 en Pana</h2>
                <p class="text-secundario/80 text-[10px] font-bold uppercase tracking-wider mt-0.5">Emitido hoy, 19:05 hrs</p>
            </div>
        </div>

        <!-- Cuerpo del Mensaje -->
        <div class="p-6">
            <p class="text-texto text-sm leading-relaxed mb-4">
                Estimados pasajeros,
            </p>
            <p class="text-texto text-sm leading-relaxed mb-4">
                Les informamos que la máquina <strong>AB-1234</strong> ha sufrido un desperfecto mecánico inevitable a la altura del sector San Manuel.
            </p>
            <p class="text-texto text-sm leading-relaxed mb-6">
                El conductor ya ha asegurado la máquina. Acabamos de despachar una unidad de respaldo desde el Terminal Culiprán que cubrirá esta ruta y recogerá a los pasajeros afectados en aproximadamente 15 minutos. Lamentamos los inconvenientes.
            </p>

            <div class="border-t border-gray-100 pt-4 mt-4">
                <p class="text-[10px] text-texto/40 font-bold uppercase text-center tracking-widest">Administración Micros Culiprán</p>
            </div>
        </div>
    </div>
</div>
@endsection
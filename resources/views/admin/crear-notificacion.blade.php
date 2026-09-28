@extends('layouts.app')
@section('title', 'Emitir Comunicado - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Nuevo Comunicado</h1>
    </div>

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Tipo de Alerta</label>
                <select class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                    <option value="info">Información General (Horarios/Tarifas)</option>
                    <option value="warning">Retraso Operativo</option>
                    <option value="danger">Emergencia / Micro en Pana</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Título del Mensaje</label>
                <input type="text" placeholder="Ej: Retraso en servicio por falla mecánica" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Cuerpo del Mensaje</label>
                <textarea rows="5" placeholder="Escriba los detalles aquí..." class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario resize-none"></textarea>
            </div>
            <button type="button" class="w-full bg-peligro text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform mt-4 flex justify-center items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Enviar a todos los pasajeros
            </button>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Asignar Jornada - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Programar Jornada</h1>
    </div>

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form class="space-y-6">
            <!-- 1. Asignación de Recursos -->
            <div class="space-y-4">
                <h3 class="font-bold text-texto text-sm border-b border-gray-100 pb-2">1. Recursos Humanos y Materiales</h3>
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Conductor</label>
                    <select class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                        <option>Juan Pérez (RUT: 12.345...)</option>
                        <option>Don Arturo (RUT: 09.876...)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Máquina Asignada</label>
                    <select class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                        <option>AB-1234 (Operativa)</option>
                        <option>XY-9876 (Operativa)</option>
                    </select>
                </div>
            </div>

            <!-- 2. Definición de la Jornada -->
            <div class="space-y-4">
                <h3 class="font-bold text-texto text-sm border-b border-gray-100 pb-2">2. Parámetros de la Jornada</h3>

                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Días de Operación (Esta Semana)</label>
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Checkboxes estilizados como botones -->
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only" checked>
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Lun</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only" checked>
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Mar</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only" checked>
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Mié</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only">
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Jue</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only">
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Vie</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only">
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Sáb</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="peer sr-only">
                            <div class="text-center bg-fondo border border-gray-200 text-texto/60 font-bold py-2 rounded-lg peer-checked:bg-primario peer-checked:text-secundario peer-checked:border-primario transition-colors text-sm">Dom</div>
                        </label>
                    </div>
                </div>

                <!-- Programación de Salidas -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider">Itinerario de Salidas</label>
                        <button type="button" class="text-[10px] font-bold text-primario bg-primario/10 px-2 py-1 rounded uppercase tracking-wider active:scale-95 transition-transform flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Vuelta
                        </button>
                    </div>

                    <div class="space-y-2">
                        <!-- Fila de Vuelta 1 -->
                        <div class="flex items-center space-x-2 bg-fondo border border-gray-200 rounded-xl p-2">
                            <span class="bg-gray-200 text-texto/60 font-black text-xs px-2 py-1.5 rounded-lg">1</span>
                            <input type="time" value="06:30" class="flex-grow bg-transparent text-texto focus:outline-none font-black px-2">
                            <button type="button" class="text-peligro/50 hover:text-peligro p-1 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Fila de Vuelta 2 (Ejemplo agregado por el usuario) -->
                        <div class="flex items-center space-x-2 bg-fondo border border-gray-200 rounded-xl p-2">
                            <span class="bg-gray-200 text-texto/60 font-black text-xs px-2 py-1.5 rounded-lg">2</span>
                            <input type="time" value="09:15" class="flex-grow bg-transparent text-texto focus:outline-none font-black px-2">
                            <button type="button" class="text-peligro/50 hover:text-peligro p-1 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-[10px] text-texto/50 font-bold mt-2 text-center uppercase">Total programado: 2 vueltas diarias</p>
                </div>
            </div>

            <button type="button" class="w-full bg-acento text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform mt-4">
                Guardar Cartola Semanal
            </button>
        </form>
    </div>
</div>
@endsection
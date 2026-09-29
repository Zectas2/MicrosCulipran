@extends('layouts.app')
@section('title', 'Editar Micro - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin/micros" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Ficha Técnica</h1>
    </div>

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Estado Operativo</label>
                <select class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                    <option value="activa">Operativa</option>
                    <option value="taller">En Taller / Mantenimiento</option>
                    <option value="baja">Dada de baja</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Patente</label>
                <input type="text" value="AB-1234" readonly class="w-full bg-gray-100 border border-gray-200 rounded-xl py-3 px-4 text-texto/60 font-bold uppercase cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Marca / Modelo</label>
                <input type="text" value="Mercedes-Benz Sprinter" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Año</label>
                    <input type="number" value="2022" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Asientos</label>
                    <input type="number" value="30" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
            </div>
            <button type="button" class="w-full bg-primario text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform mt-4">
                Actualizar Datos
            </button>
        </form>
    </div>
</div>
@endsection
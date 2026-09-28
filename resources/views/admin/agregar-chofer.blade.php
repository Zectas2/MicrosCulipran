@extends('layouts.app')
@section('title', 'Agregar Chofer - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin/choferes" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Registrar Chofer</h1>
    </div>

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Nombre Completo</label>
                <input type="text" placeholder="Ej: Juan Pérez González" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">RUT</label>
                <input type="text" placeholder="Ej: 12.345.678-9" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Edad</label>
                    <input type="number" placeholder="Ej: 45" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Teléfono</label>
                    <input type="tel" placeholder="Ej: +56 9 1234 5678" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
            </div>
            <button type="button" class="w-full bg-primario text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform mt-4">
                Guardar Chofer
            </button>
        </form>
    </div>
</div>
@endsection
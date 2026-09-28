@extends('layouts.app')
@section('title', 'Editar Chofer - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin/choferes" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Expediente de Personal</h1>
    </div>

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Estado del Trabajador</label>
                <select class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                    <option value="activo" selected>Activo</option>
                    <option value="vacaciones">En Vacaciones</option>
                    <option value="licencia">Licencia Médica</option>
                    <option value="inactivo">Desvinculado</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">RUT</label>
                <input type="text" value="12.345.678-9" readonly class="w-full bg-gray-100 border border-gray-200 rounded-xl py-3 px-4 text-texto/60 font-bold cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Nombre Completo</label>
                <input type="text" value="Juan Pérez González" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Edad</label>
                    <input type="number" value="45" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Teléfono</label>
                    <input type="tel" value="+56 9 1234 5678" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
            </div>
            <button type="button" class="w-full bg-primario text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform mt-4">
                Actualizar Expediente
            </button>
        </form>
    </div>
</div>
@endsection
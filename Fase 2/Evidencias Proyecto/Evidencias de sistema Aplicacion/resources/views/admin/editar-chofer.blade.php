@extends('layouts.app')
@section('title', 'Editar Chofer - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">
    <div class="flex items-center mb-2">
        <a href="/admin/choferes" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Expediente de Personal</h1>
    </div>

    <!-- Bloque para mostrar errores de validación -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm">
        <ul class="list-disc pl-5 text-xs font-bold">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <form method="POST" action="{{ route('admin.choferes.update', $chofer->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Estado del Trabajador</label>
                <select name="estado" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                    <option value="activo" {{ (isset($chofer->estado) && $chofer->estado === 'activo') ? 'selected' : '' }}>Activo</option>
                    <option value="vacaciones" {{ (isset($chofer->estado) && $chofer->estado === 'vacaciones') ? 'selected' : '' }}>En Vacaciones</option>
                    <option value="licencia" {{ (isset($chofer->estado) && $chofer->estado === 'licencia') ? 'selected' : '' }}>Licencia Médica</option>
                    <option value="inactivo" {{ (isset($chofer->estado) && $chofer->estado === 'inactivo') ? 'selected' : '' }}>Desvinculado</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">RUT</label>
                <!-- Retiramos el readonly y agregamos el name="rut" -->
                <input type="text" name="rut" value="{{ $chofer->rut }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Nombre Completo</label>
                <input type="text" name="nombre" value="{{ $chofer->nombre }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Edad</label>
                    <input type="number" name="edad" value="{{ $chofer->edad }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-1">Teléfono</label>
                    <input type="tel" name="telefono" value="{{ $chofer->telefono }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:border-primario font-bold">
                </div>
            </div>
            <button type="submit" class="w-full bg-primario text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform mt-4">
                Actualizar Expediente
            </button>
        </form>
    </div>
</div>
@endsection
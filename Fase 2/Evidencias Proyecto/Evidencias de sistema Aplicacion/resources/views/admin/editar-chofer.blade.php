@extends('layouts.app')
@section('title', 'Expediente Chofer - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-8">

    <!-- Cabecera Ejecutiva -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin/choferes" class="bg-gray-100 p-2.5 rounded-full text-texto/60 hover:text-primario hover:bg-primario/10 transition-colors mr-4" title="Volver a la Nómina">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-texto leading-none">Expediente de Personal</h1>
                <p class="text-xs font-bold text-texto/50 uppercase tracking-widest mt-1">Gestión de Conductores</p>
            </div>
        </div>
    </div>

    <!-- Panel de Errores de Validación -->
    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 p-4 rounded-2xl shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider mb-1">Se detectaron inconsistencias</h3>
                <ul class="text-xs text-red-700 list-disc list-inside font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulario Principal: Expediente -->
    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
        <!-- Acento superior dinámico según estado -->
        <div class="h-2 w-full {{ (isset($chofer->estado) && $chofer->estado === 'inactivo') ? 'bg-gray-400' : 'bg-primario' }}"></div>

        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.choferes.update', $chofer->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Sección: Identidad del Conductor -->
                <div>
                    <div class="flex justify-between items-end border-b border-gray-100 pb-2 mb-5">
                        <h3 class="text-sm font-black text-texto flex items-center uppercase tracking-wider">
                            <svg class="w-4 h-4 mr-2 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            Perfil del Empleado
                        </h3>

                        <!-- Botón Funcional de Llamada Rápida -->
                        @if(!empty($chofer->telefono))
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $chofer->telefono) }}" class="flex items-center text-[10px] font-black uppercase tracking-wider bg-green-100 text-green-700 px-3 py-1.5 rounded-lg active:scale-95 transition-transform">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Llamar
                        </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Nombre Completo Destacado -->
                        <div>
                            <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Nombre Completo</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $chofer->nombre) }}" required placeholder="Ej. Juan Pérez" class="w-full bg-fondo border border-gray-200 rounded-xl py-4 px-4 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-black text-lg transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">RUT (Sin puntos, con guion)</label>
                                <input type="text" name="rut" value="{{ old('rut', $chofer->rut) }}" required placeholder="Ej. 12345678-9" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Edad</label>
                                <div class="relative">
                                    <input type="number" name="edad" value="{{ old('edad', $chofer->edad) }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-4 pr-12 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span class="text-xs font-bold text-texto/40">años</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Contacto y Situación Laboral -->
                <div>
                    <h3 class="text-sm font-black text-texto border-b border-gray-100 pb-2 mb-5 flex items-center uppercase tracking-wider mt-4">
                        <svg class="w-4 h-4 mr-2 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Situación Laboral
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Teléfono de Contacto</label>
                            <input type="tel" name="telefono" value="{{ old('telefono', $chofer->telefono) }}" required placeholder="+56 9 1234 5678" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Estado del Trabajador</label>
                            <div class="relative">
                                <select name="estado" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all appearance-none cursor-pointer">
                                    <option value="activo" {{ (isset($chofer->estado) && $chofer->estado === 'activo') ? 'selected' : '' }}>🟢 Activo (En Ruta)</option>
                                    <option value="vacaciones" {{ (isset($chofer->estado) && $chofer->estado === 'vacaciones') ? 'selected' : '' }}>🔵 En Vacaciones</option>
                                    <option value="licencia" {{ (isset($chofer->estado) && $chofer->estado === 'licencia') ? 'selected' : '' }}>🟠 Licencia Médica</option>
                                    <option value="inactivo" {{ (isset($chofer->estado) && $chofer->estado === 'inactivo') ? 'selected' : '' }}>🔴 Desvinculado</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-texto/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acción Principal -->
                <div class="pt-6">
                    <button type="submit" class="w-full bg-primario text-secundario font-black py-4 rounded-xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-2 hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span>Guardar Expediente</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
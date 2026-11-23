@extends('layouts.app')
@section('title', 'Ficha Técnica - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-8">

    <!-- Cabecera -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin/micros" class="bg-gray-100 p-2.5 rounded-full text-texto/60 hover:text-primario hover:bg-primario/10 transition-colors mr-4" title="Volver a la Flota">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-texto leading-none">Ficha Técnica</h1>
                <p class="text-xs font-bold text-texto/50 uppercase tracking-widest mt-1">Edición de Vehículo</p>
            </div>
        </div>
    </div>

    <!-- Panel de Errores -->
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

    <!-- Formulario Principal -->
    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
        <!-- Acento superior -->
        <div class="h-2 w-full bg-gray-200"></div>

        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.micros.update', $vehiculo->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Sección: Identidad del Vehículo -->
                <div>
                    <h3 class="text-sm font-black text-texto border-b border-gray-100 pb-2 mb-5 flex items-center uppercase tracking-wider">
                        <svg class="w-4 h-4 mr-2 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        Identidad de la Máquina
                    </h3>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Placa Patente Decorativa (Inmutable) -->
                        <div>
                            <label class="block text-[10px] font-bold text-texto/50 uppercase tracking-widest mb-2">Placa Patente (No modificable)</label>
                            <div class="w-full bg-fondo border-2 border-gray-200 border-dashed rounded-2xl py-5 flex items-center justify-center">
                                <span class="text-4xl font-black text-texto/40 tracking-[0.2em] uppercase">{{ $vehiculo->patente }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Marca / Modelo</label>
                                <input type="text" name="marca" value="{{ old('marca', $vehiculo->marca) }}" required placeholder="Ej. Mercedes-Benz Sprinter" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Año de Registro</label>
                                <input type="number" name="anio" value="{{ old('anio', $vehiculo->anio) }}" required placeholder="Ej. 2018" class="w-full bg-fondo border border-gray-200 rounded-xl py-3 px-4 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Especificaciones Operativas -->
                <div>
                    <h3 class="text-sm font-black text-texto border-b border-gray-100 pb-2 mb-5 flex items-center uppercase tracking-wider mt-4">
                        <svg class="w-4 h-4 mr-2 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Parámetros Operativos
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Capacidad (Asientos)</label>
                            <div class="relative">
                                <input type="number" name="capacidad" value="{{ old('capacidad', $vehiculo->capacidad) }}" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-4 pr-12 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                    <span class="text-xs font-bold text-texto/40">pax</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-texto/70 uppercase tracking-wider mb-2">Estado Actual</label>
                            <div class="relative">
                                <select name="estado" required class="w-full bg-fondo border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-texto focus:outline-none focus:ring-2 focus:ring-primario/20 focus:border-primario font-bold transition-all appearance-none cursor-pointer">
                                    <option value="operativo" {{ $vehiculo->estado === 'operativo' ? 'selected' : '' }}>🟢 Operativa (Ruta)</option>
                                    <option value="en_mantencion" {{ $vehiculo->estado === 'en_mantencion' ? 'selected' : '' }}>🟠 En Taller / Mantención</option>
                                    <option value="fuera_de_servicio" {{ $vehiculo->estado === 'fuera_de_servicio' ? 'selected' : '' }}>🔴 Dada de Baja</option>
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
                        <span>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
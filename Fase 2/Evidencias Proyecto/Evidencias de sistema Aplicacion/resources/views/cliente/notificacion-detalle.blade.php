@extends('layouts.app')
@section('title', 'Comunicado Oficial - Micros Culiprán')
@section('content')
<div class="flex flex-col h-full">
    <div class="flex items-center mb-6">
        <a href="{{ route('notificaciones.index') }}" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Comunicado Oficial</h1>
    </div>

    @php
    // Determinamos el color de la cabecera según el tipo
    $headerBg = $notificacion->tipo === 'danger' ? 'bg-peligro' : ($notificacion->tipo === 'warning' ? 'bg-orange-500' : 'bg-primario');
    @endphp

    <div class="bg-secundario rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Cabecera Dinámica -->
        <div class="{{ $headerBg }} px-5 py-4 flex items-center space-x-3">
            <div class="bg-secundario/20 p-2 rounded-xl text-secundario">
                @if($notificacion->tipo === 'info')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                @endif
            </div>
            <div>
                <h2 class="text-secundario font-black text-lg leading-tight">{{ $notificacion->titulo }}</h2>
                <p class="text-secundario/80 text-[10px] font-bold uppercase tracking-wider mt-0.5">
                    Emitido el {{ $notificacion->created_at->format('d/m/Y') }} a las {{ $notificacion->created_at->format('H:i') }} hrs
                </p>
            </div>
        </div>

        <!-- Cuerpo del Mensaje -->
        <div class="p-6">
            <div class="text-texto text-sm leading-relaxed mb-6 whitespace-pre-line">
                {{ $notificacion->cuerpo }}
            </div>

            <div class="border-t border-gray-100 pt-4 mt-4">
                <p class="text-[10px] text-texto/40 font-bold uppercase text-center tracking-widest">Administración Micros Culiprán</p>
            </div>
        </div>
    </div>
</div>
@endsection
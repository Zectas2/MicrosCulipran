@extends('layouts.app')
@section('title', 'Notificaciones - Micros Culiprán')
@section('content')
<div class="flex flex-col pb-6">
    <div class="flex items-center mb-6">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Notificaciones</h1>
    </div>

    <div class="space-y-3">
        @forelse($notificaciones as $noti)
        @php
        // Lógica de colores según gravedad
        $bgClass = $noti->tipo === 'danger' ? 'bg-peligro/5 border-peligro/20' : ($noti->tipo === 'warning' ? 'bg-orange-50 border-orange-200' : 'bg-secundario border-gray-100 shadow-sm');
        $iconBg = $noti->tipo === 'danger' ? 'bg-peligro/10 text-peligro' : ($noti->tipo === 'warning' ? 'bg-orange-100 text-orange-500' : 'bg-primario/10 text-primario');
        $timeColor = $noti->tipo === 'danger' ? 'text-peligro' : 'text-texto/40';
        @endphp

        <a href="{{ route('notificaciones.show', $noti->id) }}" class="block {{ $bgClass }} p-4 rounded-2xl border active:bg-fondo transition-colors relative">
            @if($noti->tipo === 'danger')
            <span class="absolute top-4 right-4 h-2.5 w-2.5 rounded-full bg-peligro"></span>
            @endif
            <div class="flex items-start {{ $noti->tipo === 'danger' ? 'mr-4' : '' }}">
                <div class="{{ $iconBg }} p-2 rounded-lg mr-3 shrink-0">
                    @if($noti->tipo === 'danger' || $noti->tipo === 'warning')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-texto text-sm leading-tight mb-1">{{ $noti->titulo }}</h3>
                    <p class="text-xs text-texto/60 line-clamp-2">{{ $noti->cuerpo }}</p>
                    <span class="text-[10px] font-bold {{ $timeColor }} mt-2 block uppercase">{{ $noti->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>
        @empty
        <div class="p-8 text-center text-texto/50 font-bold bg-secundario rounded-2xl border border-gray-100 shadow-sm">
            No hay comunicados recientes.
        </div>
        @endforelse
    </div>
</div>
@endsection
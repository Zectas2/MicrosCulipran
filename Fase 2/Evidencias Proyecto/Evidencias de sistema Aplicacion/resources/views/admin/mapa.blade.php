@extends('layouts.app')
@section('title', 'Mapa de Flota - Admin')
@section('content')
<div class="flex flex-col h-[calc(100vh-140px)]">
    <div class="flex items-center mb-4 justify-between">
        <div class="flex items-center">
            <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-extrabold text-texto">Supervisión GPS</h1>
        </div>
        <span class="bg-acento/20 text-acento px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">4 Activas</span>
    </div>

    <div class="relative flex-grow bg-gray-300 rounded-2xl overflow-hidden shadow-inner border border-gray-300">
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-texto/50 font-bold bg-secundario/80 px-4 py-2 rounded-lg">[Mapa del Administrador]</span>
        </div>

        <div class="absolute top-4 right-4 flex flex-col space-y-2">
            <button class="bg-secundario p-3 rounded-full shadow-md text-texto border border-gray-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg></button>
        </div>
    </div>
</div>
@endsection
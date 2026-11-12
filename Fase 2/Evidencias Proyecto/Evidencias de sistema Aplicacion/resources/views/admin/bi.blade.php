@extends('layouts.app')
@section('title', 'Inteligencia Operativa - Admin')
@section('content')
<div class="flex flex-col space-y-6 pb-6">

    <!-- Cabecera -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
            <a href="/admin" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-extrabold text-texto">Inteligencia Operativa</h1>
        </div>
        <span class="bg-acento/20 text-acento px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">Hoy</span>
    </div>

    <!-- KPIs de Rendimiento Global -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <span class="text-texto/50 text-[10px] font-bold uppercase tracking-wider mb-1">Puntualidad de Salidas</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-3xl font-black text-acento">{{ $puntualidad }}%</span>
            </div>
            <span class="text-texto/60 text-xs font-bold mt-1">Cumplimiento de horario</span>
        </div>
        <div class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <span class="text-texto/50 text-[10px] font-bold uppercase tracking-wider mb-1">Vueltas Completadas</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-3xl font-black text-primario">{{ $vueltasCompletadas }}</span>
            </div>
            <span class="text-texto/60 text-xs font-bold mt-1">Total flota en el día</span>
        </div>
    </div>

    <!-- Gráfico 1: Productividad Diaria por Chofer -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-texto text-sm">Productividad por Chofer</h3>
                <p class="text-[10px] text-texto/50 uppercase tracking-wider font-bold mt-1">Total de recorridos finalizados hoy</p>
            </div>
        </div>

        <div class="relative h-48 w-full flex items-center justify-center @if(count($labelsProductividad) === 0) bg-fondo rounded-xl border border-dashed border-gray-200 @endif">
            @if(count($labelsProductividad) > 0)
            <canvas id="productividadChart"></canvas>
            @else
            <div class="text-center text-texto/40">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-xs font-bold uppercase tracking-wider">Sin recorridos finalizados</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Gráfico 2: Eficiencia de Ruta -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-texto text-sm">Tiempo Promedio del Recorrido</h3>
                <p class="text-[10px] text-texto/50 uppercase tracking-wider font-bold mt-1">Cálculo desde "Iniciar" hasta "Finalizar"</p>
            </div>
        </div>

        <div class="relative h-48 w-full flex items-center justify-center @if(count($labelsEficiencia) === 0) bg-fondo rounded-xl border border-dashed border-gray-200 @endif">
            @if(count($labelsEficiencia) > 0)
            <canvas id="eficienciaChart"></canvas>
            @else
            <div class="text-center text-texto/40">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs font-bold uppercase tracking-wider">Esperando métricas de tiempo</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Panel de Control: Mantenimiento de Máquinas -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-texto text-sm">Estado de las Máquinas</h3>
            <span class="bg-texto/5 text-texto/60 px-2 py-1 rounded-md text-[10px] font-bold uppercase">Mantenimiento</span>
        </div>

        <div class="space-y-3">
            @forelse($maquinasMantenimiento as $maquina)
            <div class="flex items-center justify-between p-3 border border-primario/30 rounded-xl bg-primario/5">
                <div class="flex items-center">
                    <div class="bg-primario/10 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-texto text-sm">{{ $maquina->patente }}</p>
                        <p class="text-[10px] text-texto/60 font-bold uppercase">
                            {{ str_replace('_', ' ', $maquina->estado) }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-texto/50 font-bold uppercase">Ingreso</p>
                    <p class="text-xs font-black text-primario mt-0.5">{{ \Carbon\Carbon::parse($maquina->updated_at)->format('d/m H:i') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <p class="text-sm font-bold text-texto/50">Toda la flota se encuentra operativa, Señor.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Integración de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Chart.defaults.font.family = "'Instrument Sans', sans-serif";
        Chart.defaults.color = '#3f3f46';

        // Sintaxis correcta y segura para inyectar JSON desde Laravel a JavaScript
        const etiquetasChoferes = {
            {
                Illuminate\ Support\ Js::from($labelsProductividad ?? [])
            }
        };
        const datosVueltas = {
            {
                Illuminate\ Support\ Js::from($datosProductividad ?? [])
            }
        };
        const etiquetasEficiencia = {
            {
                Illuminate\ Support\ Js::from($labelsEficiencia ?? [])
            }
        };
        const datosMinutos = {
            {
                Illuminate\ Support\ Js::from($datosEficiencia ?? [])
            }
        };
        // Gráfico 1: Productividad
        if (document.getElementById('productividadChart')) {
            new Chart(document.getElementById('productividadChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: etiquetasChoferes,
                    datasets: [{
                        label: 'Vueltas hoy',
                        data: datosVueltas,
                        backgroundColor: '#65a30d',
                        borderRadius: 6,
                        barThickness: 30,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 10,
                            ticks: {
                                stepSize: 2
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico 2: Eficiencia
        if (document.getElementById('eficienciaChart')) {
            new Chart(document.getElementById('eficienciaChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: etiquetasEficiencia,
                    datasets: [{
                        label: 'Minutos Promedio',
                        data: datosMinutos,
                        backgroundColor: function(context) {
                            const value = context.dataset.data[context.dataIndex];
                            return value > 50 ? '#ea580c' : '#d4d4d8';
                        },
                        borderRadius: 6,
                        barThickness: 20,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            min: 0,
                            suggestedMax: 60
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
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
                <span class="text-3xl font-black text-acento">92%</span>
            </div>
            <span class="text-texto/60 text-xs font-bold mt-1">Cumplimiento de horario</span>
        </div>
        <div class="bg-secundario p-4 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <span class="text-texto/50 text-[10px] font-bold uppercase tracking-wider mb-1">Vueltas Completadas</span>
            <div class="flex items-baseline space-x-1">
                <span class="text-3xl font-black text-primario">28</span>
            </div>
            <span class="text-texto/60 text-xs font-bold mt-1">Total flota en el día</span>
        </div>
    </div>

    <!-- Gráfico 1: Productividad Diaria por Chofer (Vueltas Completadas) -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-texto text-sm">Productividad por Chofer</h3>
                <p class="text-[10px] text-texto/50 uppercase tracking-wider font-bold mt-1">Total de recorridos finalizados hoy</p>
            </div>
        </div>
        <div class="relative h-48 w-full">
            <canvas id="productividadChart"></canvas>
        </div>
    </div>

    <!-- Gráfico 2: Eficiencia de Ruta (Tiempo Total Promedio) -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-texto text-sm">Tiempo Promedio del Recorrido</h3>
                <p class="text-[10px] text-texto/50 uppercase tracking-wider font-bold mt-1">Cálculo desde "Iniciar" hasta "Finalizar"</p>
            </div>
        </div>
        <div class="relative h-48 w-full">
            <canvas id="eficienciaChart"></canvas>
        </div>
    </div>

    <!-- Panel de Control: Mantenimiento de Máquinas -->
    <div class="bg-secundario p-5 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-texto text-sm">Estado de las Máquinas</h3>
            <span class="bg-texto/5 text-texto/60 px-2 py-1 rounded-md text-[10px] font-bold uppercase">Mantenimiento</span>
        </div>

        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 border border-gray-100 rounded-xl bg-fondo">
                <div class="flex items-center">
                    <div class="bg-acento/10 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-acento" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-texto text-sm">AB-1234</p>
                        <p class="text-[10px] text-texto/60 font-bold uppercase">Operativa / Al día</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-texto/50 font-bold uppercase">Próx. Taller</p>
                    <p class="text-xs font-bold text-texto mt-0.5">En 15 días</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 border border-primario/30 rounded-xl bg-primario/5">
                <div class="flex items-center">
                    <div class="bg-primario/10 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-texto text-sm">XY-9876</p>
                        <p class="text-[10px] text-texto/60 font-bold uppercase">Atención Preventiva</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-texto/50 font-bold uppercase">Falla rep.</p>
                    <p class="text-xs font-black text-primario mt-0.5">Neumáticos</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Integración de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Chart.defaults.font.family = "'Instrument Sans', sans-serif";
        Chart.defaults.color = '#3f3f46';

        // Gráfico 1: Productividad (Vueltas Completadas - Vertical)
        const ctxProductividad = document.getElementById('productividadChart').getContext('2d');
        new Chart(ctxProductividad, {
            type: 'bar',
            data: {
                labels: ['A. Rojas', 'J. Pérez', 'M. Soto', 'C. Díaz'],
                datasets: [{
                    label: 'Vueltas hoy',
                    data: [8, 6, 7, 7],
                    backgroundColor: '#65a30d', // acento (verde)
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
                        grid: {
                            borderDash: [4, 4],
                            color: '#e4e4e7'
                        },
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

        // Gráfico 2: Eficiencia de Ruta (Tiempo por Vuelta - Horizontal)
        const ctxEficiencia = document.getElementById('eficienciaChart').getContext('2d');
        new Chart(ctxEficiencia, {
            type: 'bar',
            data: {
                labels: ['A. Rojas', 'J. Pérez', 'M. Soto', 'C. Díaz'],
                datasets: [{
                    label: 'Minutos Promedio',
                    data: [42, 53, 46, 44],
                    // Color condicional: Naranja si supera los 50 minutos
                    backgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        return value > 50 ? '#ea580c' : '#d4d4d8';
                    },
                    borderRadius: 6,
                    barThickness: 20,
                }]
            },
            options: {
                indexAxis: 'y', // Gráfico horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        min: 30,
                        max: 60,
                        grid: {
                            borderDash: [4, 4],
                            color: '#e4e4e7'
                        }
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
    });
</script>
@endsection
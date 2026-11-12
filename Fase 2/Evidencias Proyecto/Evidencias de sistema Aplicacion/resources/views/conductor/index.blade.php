@extends('layouts.app')
@section('title', 'Panel de Conductor - Micros Culiprán')

@section('content')

@php
// Extraemos la información del turno actual
$turnoActual = $turnosHoy->whereIn('estado', ['programado', 'en_ruta'])->first();
$vueltasTotales = $turnosHoy->count();

// Motor de cálculo de atraso en tiempo real
$estaAtrasado = false;
$minutosAtraso = 0;

if ($turnoActual && $turnoActual->estado === 'programado' && $turnoActual->hora_programada) {
$ahora = \Carbon\Carbon::now('America/Santiago');
$programada = \Carbon\Carbon::parse($turnoActual->hora_programada)->timezone('America/Santiago');

// Si la diferencia es positiva, la hora actual superó a la hora programada
$diferencia = $programada->diffInMinutes($ahora, false);

if ($diferencia > 0) {
$estaAtrasado = true;
$minutosAtraso = round($diferencia);
}
}
@endphp

<div class="flex flex-col space-y-6 max-w-md mx-auto w-full pb-6">

    <!-- Notificación de Éxito al iniciar ruta -->
    @if(session('exito'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm text-center">
        <span class="block font-bold text-sm">{{ session('exito') }}</span>
    </div>
    @endif

    <!-- Notificación de Emergencia -->
    @if(session('alerta'))
    <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm text-center mb-4 animate-pulse">
        <span class="block font-black text-sm">{{ session('alerta') }}</span>
    </div>
    @endif

    <!-- Tarjeta Interactiva de Información de Ruta Asignada -->
    @if($turnoActual)
    <a href="/conductor/itinerario" class="block bg-secundario rounded-3xl shadow-sm border {{ $estaAtrasado ? 'border-red-400' : 'border-gray-200' }} p-5 active:scale-95 transition-transform relative hover:border-primario/50">
        <!-- Ícono indicador de lista/expansión -->
        <div class="absolute top-4 right-4 bg-gray-100 p-1.5 rounded-lg text-texto/40">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
        </div>

        <div class="flex justify-between items-center mb-3 pr-10">
            <span class="text-xs font-bold uppercase tracking-wider text-primario bg-primario/10 px-3 py-1 rounded-full flex items-center">
                @if($turnoActual->estado === 'en_ruta')
                <!-- Semáforo de conexión GPS inyectado aquí -->
                <span id="indicador-red" class="w-2 h-2 rounded-full bg-gray-400 mr-2 animate-pulse" title="Esperando GPS..."></span>
                Ruta Activa
                @else
                Turno Asignado
                @endif
            </span>
            <span class="text-xs font-bold {{ $estaAtrasado ? 'text-red-500 animate-pulse' : 'text-acento' }}">
                {{ $turnoActual->estado === 'en_ruta' ? 'En Tránsito' : ($estaAtrasado ? 'ATRASADO' : 'Pendiente') }}
            </span>
        </div>

        <h2 class="text-xl font-black text-texto">Culiprán &rarr; Melipilla</h2>

        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between text-sm text-texto/80">
            <div>
                <p class="text-[10px] uppercase font-bold text-texto/50">Máquina</p>
                <p class="font-bold">{{ $turnoActual->patente }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] uppercase font-bold text-texto/50">Hora Programada</p>
                <p class="font-bold {{ $estaAtrasado ? 'text-red-600' : '' }}">{{ \Carbon\Carbon::parse($turnoActual->hora_programada)->format('H:i') }} hrs</p>
            </div>
        </div>

        <!-- ALERTA DE ATRASO DINÁMICA -->
        @if($estaAtrasado)
        <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-3 flex items-center justify-between animate-pulse shadow-sm">
            <div class="flex items-center">
                <div class="bg-red-100 p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-black text-red-800 uppercase tracking-wider">Retraso de Salida</span>
            </div>
            <span class="text-xl font-black text-red-600">-{{ $minutosAtraso }} min</span>
        </div>
        @else
        <!-- Indicador visual de más contenido normal -->
        <div class="mt-4 bg-fondo rounded-xl p-2 text-center border border-gray-100 flex justify-center items-center">
            <span class="text-[10px] font-bold text-texto/60 uppercase tracking-widest">Tocar para ver itinerario ({{ $vueltasTotales }} Vueltas)</span>
        </div>
        @endif
    </a>
    @else
    <!-- Tarjeta de Jornada Finalizada / Sin Turnos -->
    <div class="block bg-secundario rounded-3xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-xl font-black text-texto">Jornada Completada</h2>
        <p class="text-sm text-texto/60 mt-2">No tiene recorridos pendientes para el día de hoy.</p>
    </div>
    @endif

    <!-- Bloque de Botones Operativos -->
    <div class="flex flex-col space-y-4">

        <!-- Botón 1: Iniciar Ruta (Funcional con Formulario) -->
        @if($turnoActual && $turnoActual->estado === 'programado')
        <form action="{{ route('conductor.iniciar', $turnoActual->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full {{ $estaAtrasado ? 'bg-red-600 hover:bg-red-700' : 'bg-acento hover:bg-acento/90' }} text-secundario text-xl font-black py-6 rounded-3xl shadow-md active:scale-95 transition-all flex items-center justify-center space-x-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $estaAtrasado ? 'INICIAR RUTA URGENTE' : 'INICIAR RUTA' }}</span>
            </button>
        </form>
        @elseif(!$turnoActual)
        <!-- Botón deshabilitado si no tiene turnos -->
        <button disabled class="w-full bg-gray-300 text-gray-500 text-xl font-black py-6 rounded-3xl shadow-sm flex items-center justify-center space-x-3 cursor-not-allowed">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>SIN RUTA PENDIENTE</span>
        </button>
        @else
        <!-- No mostramos el botón iniciar si ya está en ruta -->
        @endif

        <!-- Botón 2: Finalizar Ruta Actual (Funcional) -->
        @if($turnoActual && $turnoActual->estado === 'en_ruta')
        <form action="{{ route('conductor.finalizar', $turnoActual->id) }}" method="POST" onsubmit="return confirm('¿Confirma que ha llegado al terminal y desea finalizar esta ruta?');">
            @csrf
            <button type="submit" class="w-full bg-texto text-secundario text-base font-bold py-4 rounded-3xl shadow-sm active:scale-95 transition-transform animate-pulse bg-green-700">
                Finalizar Ruta Actual
            </button>
        </form>
        @else
        <!-- Botón deshabilitado si no está en ruta -->
        <button disabled class="w-full bg-gray-300 text-gray-500 text-base font-bold py-4 rounded-3xl shadow-sm cursor-not-allowed opacity-70">
            Finalizar Ruta Actual
        </button>
        @endif

        <!-- Separador visual de seguridad -->
        <div class="pt-4">
            @if($turnoActual)
            <!-- Botón 3: Alerta de Emergencia (Funcional) -->
            <form action="{{ route('conductor.pana', $turnoActual->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de reportar una avería? Esto alertará a la central de forma inmediata.');">
                @csrf
                <button type="submit" class="w-full bg-peligro text-secundario text-lg font-black py-5 rounded-3xl shadow-md active:scale-95 transition-transform flex items-center justify-center space-x-3 border-2 border-red-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>MICRO EN PANA</span>
                </button>
            </form>
            @else
            <!-- Botón deshabilitado si no hay turno asignado -->
            <button disabled class="w-full bg-red-300 text-secundario text-lg font-black py-5 rounded-3xl shadow-sm flex items-center justify-center space-x-3 border-2 border-red-300 cursor-not-allowed opacity-70">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>MICRO EN PANA</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Nota de Asistencia Operativa -->
    <div class="text-center px-4 mt-2">
        <p class="text-[10px] text-texto/50 leading-relaxed font-bold uppercase" id="estado-conexion-texto">
            Buscando satélites GPS...
        </p>
    </div>

</div>

<!-- Motor de Telemetría GPS en Tiempo Real -->
@if($turnoActual && $turnoActual->estado === 'en_ruta')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // =================================================================
        // MÓDULO DE SIMULACIÓN (HERRAMIENTA DE TESTEO)
        // =================================================================
        const MODO_SIMULACION = true; // <-- Cambie a 'false' para volver al GPS real

        let indiceRuta = 0;
        // Coordenadas extraídas de la Ruta G-60 (Culiprán -> Melipilla Centro)
        const rutaCulipranMelipilla = [{
                lat: -33.785000,
                lng: -71.245000
            },
            {
                lat: -33.765000,
                lng: -71.238000
            },
            {
                lat: -33.742000,
                lng: -71.231000
            },
            {
                lat: -33.718000,
                lng: -71.225000
            },
            {
                lat: -33.691356,
                lng: -71.216710
            }
        ];
        // =================================================================

        const indicadorRed = document.getElementById('indicador-red');
        const estadoTexto = document.getElementById('estado-conexion-texto');

        const actualizarSemaforo = (estado) => {
            if (!indicadorRed || !estadoTexto) return;
            indicadorRed.classList.remove('bg-gray-400', 'bg-green-500', 'bg-red-500', 'bg-yellow-500');

            switch (estado) {
                case 'OK':
                    indicadorRed.classList.add('bg-green-500');
                    estadoTexto.innerText = MODO_SIMULACION ? "SIMULACIÓN ACTIVA: Emitiendo GPS falso." : "Conexión estable. Transmitiendo GPS cada 5s.";
                    break;
                case 'OFFLINE':
                    indicadorRed.classList.add('bg-red-500');
                    estadoTexto.innerText = "Sin internet. Reconectando en segundo plano...";
                    break;
                case 'LENTO':
                    indicadorRed.classList.add('bg-yellow-500');
                    estadoTexto.innerText = "Conexión inestable. El mapa podría verse desfasado.";
                    break;
            }
        };

        const enviarDatosAlServidor = async (latitud, longitud, velocidad) => {
            // INYECCIÓN DE DIAGNÓSTICO: Registro de preparación
            console.log("🚀 [Telemetria] Preparando envío -> Lat: " + latitud + ", Lng: " + longitud + ", Vel: " + velocidad + " km/h");

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 4000);

            try {
                const response = await fetch("{{ route('conductor.telemetria', $turnoActual->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        lat: latitud,
                        lng: longitud,
                        velocidad: velocidad
                    }),
                    signal: controller.signal
                });

                clearTimeout(timeoutId);

                if (response.ok) {
                    // INYECCIÓN DE DIAGNÓSTICO: Registro de éxito
                    const data = await response.json().catch(() => ({}));
                    console.log("✅ [Servidor OK] Paquete procesado correctamente:", data);
                    actualizarSemaforo('OK');
                } else {
                    // INYECCIÓN DE DIAGNÓSTICO: Registro de error HTTP
                    console.warn("⚠️ [Servidor Error] Código de respuesta HTTP:", response.status);
                    actualizarSemaforo('LENTO');
                }
            } catch (error) {
                if (error.name === 'AbortError') {
                    // INYECCIÓN DE DIAGNÓSTICO: Registro de timeout
                    console.error("⏱️ [Timeout] La petición tardó más de 4 segundos en responder.");
                    actualizarSemaforo('LENTO');
                } else {
                    // INYECCIÓN DE DIAGNÓSTICO: Registro de desconexión
                    console.error("❌ [Fallo de Red] No se pudo conectar con el servidor:", error);
                    actualizarSemaforo('OFFLINE');
                }
            }
        };

        const procesarUbicacion = () => {
            if (MODO_SIMULACION) {
                // SIMULADOR: Toma un punto de la ruta y avanza
                const coords = rutaCulipranMelipilla[indiceRuta];
                enviarDatosAlServidor(coords.lat, coords.lng, 45.0); // Simula 45 km/h

                indiceRuta++;
                if (indiceRuta >= rutaCulipranMelipilla.length) {
                    indiceRuta = 0; // Reinicia el ciclo al llegar al final
                }
            } else {
                // PRODUCCIÓN: Lee la antena GPS real del dispositivo
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function(posicion) {
                            const lat = posicion.coords.latitude;
                            const lng = posicion.coords.longitude;
                            const vel = posicion.coords.speed ? (posicion.coords.speed * 3.6).toFixed(2) : 0;
                            enviarDatosAlServidor(lat, lng, vel);
                        },
                        function(error) {
                            console.warn('Señal GPS perdida o denegada:', error.message);
                            if (estadoTexto) estadoTexto.innerText = "Error GPS: Asegúrese de estar al aire libre.";
                            if (indicadorRed) indicadorRed.classList.replace('bg-green-500', 'bg-red-500');
                        }, {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 4500
                        }
                    );
                } else {
                    alert("El navegador de su dispositivo no soporta la lectura satelital GPS.");
                }
            }
        };

        // Disparo inicial y bucle de 5 segundos
        procesarUbicacion();
        setInterval(procesarUbicacion, 5000);
    });
</script>
@endif
@endsection
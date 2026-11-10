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
        <span class="bg-acento/20 text-acento px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider" id="contador-activas">
            {{ $cantidadActivas }} Activas
        </span>
    </div>

    <!-- Contenedor del Mapa Admin -->
    <div class="relative flex-grow bg-gray-200 rounded-2xl overflow-hidden shadow-inner border border-gray-300">
        <div id="map-admin" class="w-full h-full"></div>
    </div>
</div>

<script>
    window.initMapAdmin = function() {
        const culipranCenter = {
            lat: -33.7450,
            lng: -71.2280
        };

        const map = new google.maps.Map(document.getElementById("map-admin"), {
            zoom: 11,
            center: culipranCenter,
            mapId: "ADMIN_MAP_ID",
            disableDefaultUI: false,
            zoomControl: true,
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            gestureHandling: 'greedy'
        });

        const flotaActiva = {};
        const microsIniciales = @json($microsActivas ?? []);

        function actualizarMicro(vehiculoId, patente, lat, lng, velocidad, conductor = 'En tránsito') {
            if (flotaActiva[vehiculoId]) {
                // CORRECCIÓN 1: AdvancedMarkerElement requiere un objeto literal, no new google.maps.LatLng
                flotaActiva[vehiculoId].position = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };
                console.log(`📍 [Mapa Actualizado] Máquina ${patente} movida en el mapa.`);
            } else {
                const iconDiv = document.createElement("div");
                iconDiv.style.cursor = "pointer";

                iconDiv.innerHTML = `
                        <div class="relative active:scale-90 transition-transform" style="width: 52px; height: 52px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <svg width="44" height="44" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0px 4px 5px rgba(0, 0, 0, 0.4));">
                                <rect x="4" y="16" width="56" height="28" rx="6" fill="#0284c7" />
                                <rect x="4" y="30" width="56" height="6" fill="#ffffff" />
                                <rect x="4" y="30" width="16" height="6" fill="#facc15" />
                                <rect x="8" y="20" width="10" height="8" rx="2" fill="#1e293b" />
                                <rect x="22" y="20" width="10" height="8" rx="2" fill="#1e293b" />
                                <rect x="36" y="20" width="10" height="8" rx="2" fill="#1e293b" />
                                <rect x="50" y="20" width="6" height="8" rx="2" fill="#1e293b" />
                                <circle cx="16" cy="44" r="7" fill="#0f172a" />
                                <circle cx="16" cy="44" r="3.5" fill="#94a3b8" />
                                <circle cx="48" cy="44" r="7" fill="#0f172a" />
                                <circle cx="48" cy="44" r="3.5" fill="#94a3b8" />
                                <path d="M60 34 C60 34, 62 34, 62 36 L62 40 C62 40, 60 40, 60 40 Z" fill="#fef08a" />
                                <rect x="4" y="34" width="2" height="6" fill="#ef4444" />
                            </svg>
                            <div class="absolute -top-3 bg-texto/95 text-secundario text-[10px] font-black px-1.5 py-0.5 rounded border border-gray-600 shadow-md whitespace-nowrap">
                                ${patente}
                            </div>
                        </div>
                    `;

                const marker = new google.maps.marker.AdvancedMarkerElement({
                    position: {
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    },
                    map: map,
                    title: patente,
                    content: iconDiv
                });

                const infoWindow = new google.maps.InfoWindow();

                marker.element.addEventListener("gmp-click", () => {
                    const infoHTML = `
                        <div class="p-2 min-w-[180px]">
                            <div class="flex justify-between items-start border-b border-gray-200 pb-2 mb-2">
                                <div>
                                    <strong class="text-base text-gray-800">${patente}</strong>
                                    <div class="text-[10px] uppercase font-bold text-gray-500 mt-1">ID: ${vehiculoId}</div>
                                </div>
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-black uppercase">GPS OK</span>
                            </div>
                            <div class="space-y-1 mt-2">
                                <div class="text-xs text-gray-700"><span class="font-bold">Chofer:</span> ${conductor}</div>
                                <div class="text-xs text-gray-700"><span class="font-bold">Velocidad:</span> <span class="text-blue-600">${velocidad} km/h</span></div>
                            </div>
                        </div>
                    `;
                    infoWindow.setContent(infoHTML);
                    infoWindow.open(map, marker);
                });

                flotaActiva[vehiculoId] = marker;
                document.getElementById('contador-activas').innerText = `${Object.keys(flotaActiva).length} Activas`;
            }
        }

        // 1. Cargar estado inicial
        microsIniciales.forEach(micro => {
            actualizarMicro(micro.vehiculoId, micro.patente, micro.lat, micro.lng, micro.velocidad, micro.conductor);
        });

        // 2. Conectar radar en tiempo real
        setTimeout(() => {
            if (window.Echo) {
                window.Echo.channel('flota-vivo')
                    .listen('.PosicionActualizada', (evento) => {
                        console.log("📡 [Reverb Admin] Evento RAW:", evento);

                        // CORRECCIÓN 2: Normalizamos las variables por si el backend las nombra distinto
                        const lat = evento.lat || evento.latitud;
                        const lng = evento.lng || evento.longitud;
                        const vId = evento.vehiculoId || evento.vehiculo_id || evento.id;

                        if (lat && lng && vId) {
                            actualizarMicro(vId, evento.patente, lat, lng, evento.velocidad);
                        } else {
                            console.error("⚠️ Faltan datos en el evento. Verifique el backend.", evento);
                        }
                    });
            }
        }, 500);
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('VITE_GOOGLE_MAPS_KEY') }}&loading=async&libraries=marker&callback=initMapAdmin" async defer></script>
@endsection
@extends('layouts.app')
@section('title', 'Mapa en Vivo - Micros Culiprán')
@section('content')
<div class="flex flex-col h-[calc(100vh-140px)]">
    <!-- Cabecera con botón de retroceso -->
    <div class="flex items-center mb-4">
        <a href="/" class="bg-primario/10 p-2 rounded-full text-primario mr-3 active:scale-95 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-xl font-extrabold text-texto">Mapa en Vivo</h1>
    </div>

    <!-- Contenedor del Mapa -->
    <div class="relative flex-grow bg-gray-200 rounded-2xl overflow-hidden shadow-inner border border-gray-300">
        <div id="map" class="w-full h-full"></div>

        <!-- Tarjeta Flotante Inferior -->
        <div class="absolute bottom-4 left-4 right-4 bg-secundario rounded-xl shadow-lg p-4 border border-gray-100 z-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="bg-acento w-3 h-3 rounded-full animate-pulse mr-2"></span>
                    <p class="font-bold text-texto text-sm" id="micros-counter">0 Micros en ruta</p>
                </div>
                <span class="text-xs font-bold text-primario bg-primario/10 px-2 py-1 rounded-md">Actualizado ahora</span>
            </div>
        </div>
    </div>
</div>

<script>
    window.initMap = function() {
        // Centro predeterminado por si el usuario rechaza el GPS (Melipilla/Culiprán)
        const culipranCenter = {
            lat: -33.689,
            lng: -71.215
        };

        // Inicialización del Mapa
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: culipranCenter,
            mapId: "DEMO_MAP_ID",
            disableDefaultUI: false,
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            gestureHandling: 'greedy'
        });

        // 1. RASTREO DEL USUARIO (Local)
        let marcadorUsuario = null;

        if ("geolocation" in navigator) {
            navigator.geolocation.watchPosition(
                (posicion) => {
                    const ubicacionActual = {
                        lat: posicion.coords.latitude,
                        lng: posicion.coords.longitude
                    };

                    if (!marcadorUsuario) {
                        const usuarioDiv = document.createElement("div");
                        usuarioDiv.innerHTML = `<div style="width: 16px; height: 16px; background-color: #ef4444; border: 2px solid #ffffff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`;

                        marcadorUsuario = new google.maps.marker.AdvancedMarkerElement({
                            position: ubicacionActual,
                            map: map,
                            title: "Su Ubicación",
                            content: usuarioDiv
                        });

                        map.setCenter(ubicacionActual);
                    } else {
                        marcadorUsuario.position = ubicacionActual;
                    }
                },
                (error) => {
                    console.warn("Señal GPS del pasajero denegada o no disponible. El mapa mostrará las micros de todos modos.", error);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 5000
                }
            );
        }

        // 2. RASTREO DE LA FLOTA (Estado Inicial + Tiempo Real)
        const flotaActiva = {};

        // Inyectamos la variable del Controlador con un arreglo vacío de respaldo para evitar errores de renderizado
        const microsIniciales = @json($microsActivas ?? []);

        // Función maestra centralizada para crear o actualizar marcadores
        function actualizarMicro(vehiculoId, patente, lat, lng, velocidad) {
            if (flotaActiva[vehiculoId]) {
                // Actualizar posición de la micro existente
                flotaActiva[vehiculoId].position = new google.maps.LatLng(lat, lng);
            } else {
                // Renderizar nueva micro con diseño vectorial SVG
                const iconDiv = document.createElement("div");
                iconDiv.style.position = "relative";
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

                // Corrección aplicada: Sustitución de 'click' por 'gmp-click'
                marker.element.addEventListener("gmp-click", () => {
                    infoWindow.setContent(`
                        <div class="p-2 min-w-[120px]">
                            <strong class="text-sm text-gray-800">${patente}</strong>
                            <div class="text-xs font-semibold text-gray-700 mt-1">
                                <span class="text-gray-500 font-normal">Velocidad:</span> ${velocidad} km/h
                            </div>
                        </div>
                    `);
                    infoWindow.open(map, marker);
                });

                flotaActiva[vehiculoId] = marker;
                document.getElementById('micros-counter').innerText = `${Object.keys(flotaActiva).length} Micros en ruta`;
            }
        }

        // Fase 1: Restaurar posiciones iniciales al cargar la página
        microsIniciales.forEach(micro => {
            actualizarMicro(micro.vehiculoId, micro.patente, micro.lat, micro.lng, micro.velocidad);
        });

        // Fase 2: Escuchar actualizaciones satelitales mediante HTTP Polling
        setInterval(() => {
            fetch('/mapa/datos-vivo')
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        data.forEach(micro => {
                            actualizarMicro(micro.vehiculoId, micro.patente, micro.lat, micro.lng, micro.velocidad);
                        });
                    }
                })
                .catch(error => console.error("⚠️ Error consultando el radar GPS:", error));
        }, 5000); // Consulta silenciosa cada 5 segundos
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('VITE_GOOGLE_MAPS_KEY') }}&loading=async&libraries=marker&callback=initMap" async defer></script>
@endsection
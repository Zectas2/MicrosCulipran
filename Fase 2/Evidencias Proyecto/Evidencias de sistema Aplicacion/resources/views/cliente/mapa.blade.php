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
                    <p class="font-bold text-texto text-sm" id="micros-counter">2 Micros en ruta</p>
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
            // watchPosition solicita permiso automáticamente y rastrea cambios constantes
            navigator.geolocation.watchPosition(
                (posicion) => {
                    const ubicacionActual = {
                        lat: posicion.coords.latitude,
                        lng: posicion.coords.longitude
                    };

                    if (!marcadorUsuario) {
                        // Creación del marcador del usuario si es la primera vez que detecta señal
                        const usuarioDiv = document.createElement("div");
                        usuarioDiv.innerHTML = `<div style="width: 16px; height: 16px; background-color: #ef4444; border: 2px solid #ffffff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`;

                        marcadorUsuario = new google.maps.marker.AdvancedMarkerElement({
                            position: ubicacionActual,
                            map: map,
                            title: "Su Ubicación",
                            content: usuarioDiv
                        });

                        // Centramos la cámara en el usuario la primera vez que lo encuentra
                        map.setCenter(ubicacionActual);
                    } else {
                        // Si ya existe, simplemente actualizamos su posición en el mapa
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

        // 2. RASTREO DE LA FLOTA (Vía Laravel Reverb)
        const flotaActiva = {};

        setTimeout(() => {
            if (window.Echo) {
                console.log("Conectado al radar de Laravel Reverb. Esperando coordenadas...");

                window.Echo.channel('flota-vivo')
                    .listen('.PosicionActualizada', (evento) => {

                        // Si la micro ya existe, animamos su nueva posición
                        if (flotaActiva[evento.vehiculoId]) {
                            flotaActiva[evento.vehiculoId].position = new google.maps.LatLng(evento.latitud, evento.longitud);
                        } else {
                            // Si no existe, creamos su marcador
                            const colorMicro = "#0284c7"; // Azul corporativo

                            const iconDiv = document.createElement("div");
                            iconDiv.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="${colorMicro}" stroke="#ffffff" stroke-width="1.5" class="drop-shadow-md"><path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/></svg>`;

                            const marker = new google.maps.marker.AdvancedMarkerElement({
                                position: {
                                    lat: parseFloat(evento.latitud),
                                    lng: parseFloat(evento.longitud)
                                },
                                map: map,
                                title: evento.patente,
                                content: iconDiv
                            });

                            // Cuadro de información al tocar la micro (Sin cálculo de API)
                            const infoWindow = new google.maps.InfoWindow();
                            marker.element.addEventListener("click", () => {
                                infoWindow.setContent(`
                                    <div class="p-2 min-w-[120px]">
                                        <strong class="text-sm text-gray-800">${evento.patente}</strong>
                                        <div class="text-xs font-semibold text-gray-700 mt-1">
                                            <span class="text-gray-500 font-normal">Velocidad:</span> ${evento.velocidad} km/h
                                        </div>
                                    </div>
                                `);
                                infoWindow.open(map, marker);
                            });

                            flotaActiva[evento.vehiculoId] = marker;

                            // Actualizar el contador visual
                            document.getElementById('micros-counter').innerText = `${Object.keys(flotaActiva).length} Micros en ruta`;
                        }
                    });
            } else {
                console.error("El radar (Laravel Echo) no está disponible. Asegúrese de importar Vite en su layout.");  
            }
        }, 500);
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCX0SeguX9YMrSGpiiOwwe3eU-ei5YhL4&loading=async&libraries=marker&callback=initMap" async defer></script>
@endsection
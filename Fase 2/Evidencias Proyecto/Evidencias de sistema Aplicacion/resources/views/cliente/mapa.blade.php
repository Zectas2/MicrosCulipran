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
        // Configuraciones base
        const culipranCenter = {
            lat: -33.7450,
            lng: -71.2280
        };

        // Ubicación actual simulada del usuario (El Molino)
        const ubicacionUsuario = {
            lat: -33.780303,
            lng: -71.242930
        };
        const destinoUsuario = "Melipilla";

        // Inicialización del Mapa con gestureHandling para permitir un solo dedo
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: culipranCenter,
            mapId: "DEMO_MAP_ID",
            disableDefaultUI: false,
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            gestureHandling: 'greedy' // <--- Esto desactiva el requerimiento de los dos dedos
        });

        const distanceMatrixService = new google.maps.DistanceMatrixService();
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#0284c7',
                strokeOpacity: 0.8,
                strokeWeight: 5
            }
        });

        // 1. Trazado de la ruta en el mapa a partir del arreglo inyectado por Laravel
        const rutaCoordenadasJSON = @json($coordenadasArray ?? []);

        if (rutaCoordenadasJSON && rutaCoordenadasJSON.length > 0) {

            // Crear marcadores visuales MODERNIZADOS (AdvancedMarkerElement) para cada paradero
            rutaCoordenadasJSON.forEach(punto => {

                const paraderoDiv = document.createElement("div");
                paraderoDiv.innerHTML = `<div style="width: 12px; height: 12px; background-color: #ffffff; border: 2px solid #0284c7; border-radius: 50%;"></div>`;

                new google.maps.marker.AdvancedMarkerElement({
                    position: {
                        lat: punto.lat,
                        lng: punto.lng
                    },
                    map: map,
                    title: punto.nombre,
                    content: paraderoDiv
                });
            });

            // Generar las paradas intermedias (Waypoints) excluyendo el inicio y el fin
            const waypoints = rutaCoordenadasJSON.slice(1, -1).map(punto => ({
                location: new google.maps.LatLng(punto.lat, punto.lng),
                stopover: true
            }));

            // Solicitar a Google que dibuje la línea azul por las calles
            directionsService.route({
                origin: new google.maps.LatLng(rutaCoordenadasJSON[0].lat, rutaCoordenadasJSON[0].lng),
                destination: new google.maps.LatLng(rutaCoordenadasJSON[rutaCoordenadasJSON.length - 1].lat, rutaCoordenadasJSON[rutaCoordenadasJSON.length - 1].lng),
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                }
            });
        }

        // Marcador visual del Usuario MODERNIZADO (AdvancedMarkerElement)
        const usuarioDiv = document.createElement("div");
        usuarioDiv.innerHTML = `<div style="width: 16px; height: 16px; background-color: #ef4444; border: 2px solid #ffffff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`;

        new google.maps.marker.AdvancedMarkerElement({
            position: ubicacionUsuario,
            map: map,
            title: "Su Paradero",
            content: usuarioDiv
        });

        // 2. Micros en tiempo real (Lógica de filtrado y tiempo retenida)
        const activeBuses = [{
                id: "Micro 01",
                lat: -33.69135016609804,
                lng: -71.21672129818464,
                status: "Hacia Culiprán",
                horarioSalida: "13:15 hrs",
                direccion: "Culipran"
            },
            {
                id: "Micro 02",
                lat: -33.77430209935964,
                lng: -71.24064190190211,
                status: "Hacia Melipilla Centro",
                horarioSalida: "13:30 hrs",
                direccion: "Melipilla"
            }
        ];

        activeBuses.forEach(bus => {
            const colorMicro = bus.direccion === "Melipilla" ? "#0284c7" : "#16a34a";

            const iconDiv = document.createElement("div");
            iconDiv.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="${colorMicro}" stroke="#ffffff" stroke-width="1.5" class="drop-shadow-md"><path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/></svg>`;

            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: {
                    lat: bus.lat,
                    lng: bus.lng
                },
                map: map,
                title: bus.id,
                content: iconDiv
            });

            const infoWindow = new google.maps.InfoWindow();

            // Corrección: Usamos addEventListener nativo en el elemento DOM del marcador en lugar de addListener
            marker.element.addEventListener("click", () => {
                const esDireccionCorrecta = bus.direccion === destinoUsuario;

                let infoHTML = `
                    <div class="p-2 min-w-[160px]">
                        <strong class="text-sm text-gray-800">${bus.id}</strong>
                        <div class="text-xs text-gray-600 mt-1 pb-1 border-b border-gray-200">${bus.status}</div>
                        <div class="text-xs font-semibold text-gray-700 mt-2">
                            <span class="text-gray-500 font-normal">Salida:</span> ${bus.horarioSalida}
                        </div>
                `;

                if (!esDireccionCorrecta) {
                    infoHTML += `
                        <div class="text-xs font-bold text-gray-400 mt-2">
                            Vehículo en dirección opuesta
                        </div>
                    </div>`;
                    infoWindow.setContent(infoHTML);
                    infoWindow.open(map, marker);
                } else {
                    infoHTML += `
                        <div class="text-xs font-semibold text-primario mt-1 animate-pulse">
                            <span class="text-gray-500 font-normal">Evaluando posición...</span>
                        </div>
                    </div>`;
                    infoWindow.setContent(infoHTML);
                    infoWindow.open(map, marker);

                    distanceMatrixService.getDistanceMatrix({
                        origins: [{
                            lat: bus.lat,
                            lng: bus.lng
                        }],
                        destinations: [ubicacionUsuario],
                        travelMode: google.maps.TravelMode.DRIVING,
                    }, (response, status) => {
                        if (status === "OK" && response.rows[0].elements[0].status === "OK") {
                            const tiempoEnSegundos = response.rows[0].elements[0].duration.value;
                            const tiempoEstimadoTexto = response.rows[0].elements[0].duration.text;
                            let mensajeLlegada = `<span class="text-gray-500 font-normal">Llegada en:</span> ${tiempoEstimadoTexto}`;

                            if (tiempoEnSegundos > 1800) {
                                mensajeLlegada = `<span class="text-red-500">El vehículo ya pasó su ubicación</span>`;
                            }

                            infoWindow.setContent(`
                                <div class="p-2 min-w-[160px]">
                                    <strong class="text-sm text-gray-800">${bus.id}</strong>
                                    <div class="text-xs text-gray-600 mt-1 pb-1 border-b border-gray-200">${bus.status}</div>
                                    <div class="text-xs font-semibold text-gray-700 mt-2">
                                        <span class="text-gray-500 font-normal">Salida:</span> ${bus.horarioSalida}
                                    </div>
                                    <div class="text-xs font-bold text-primario mt-1">
                                        ${mensajeLlegada}
                                    </div>
                                </div>
                            `);
                        }
                    });
                }
            });
        });
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCX0SeguX9YMrSGpiiOwwe3eU-ei5YhL4&loading=async&libraries=marker&callback=initMap" async defer></script>
@endsection
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
        <span class="bg-acento/20 text-acento px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
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
            mapTypeControl: true, // Útil para el administrador
            streetViewControl: false,
            fullscreenControl: true,
            gestureHandling: 'greedy' // Desactiva los dos dedos
        });

        // Inyectamos la flota en tránsito desde Laravel
        const flotaActiva = @json($datosMapa ?? []);

        flotaActiva.forEach(micro => {
            const iconDiv = document.createElement("div");
            // Usamos un icono diferente para diferenciar el panel admin (color oscuro)
            iconDiv.innerHTML = `<svg width="32" height="32" viewBox="0 0 24 24" fill="#1f2937" stroke="#ffffff" stroke-width="2" class="drop-shadow-lg"><path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/></svg>`;

            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: {
                    lat: micro.lat,
                    lng: micro.lng
                },
                map: map,
                title: micro.patente,
                content: iconDiv
            });

            const infoWindow = new google.maps.InfoWindow();

            marker.element.addEventListener("click", () => {
                const infoHTML = `
                    <div class="p-2 min-w-[180px]">
                        <div class="flex justify-between items-start border-b border-gray-200 pb-2 mb-2">
                            <div>
                                <strong class="text-base text-gray-800">${micro.patente}</strong>
                                <div class="text-[10px] uppercase font-bold text-gray-500 mt-1">${micro.id}</div>
                            </div>
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-black uppercase">GPS OK</span>
                        </div>
                        <div class="space-y-1 mt-2">
                            <div class="text-xs text-gray-700"><span class="font-bold">Chofer:</span> ${micro.conductor}</div>
                            <div class="text-xs text-gray-700"><span class="font-bold">Velocidad:</span> <span class="text-blue-600">${micro.velocidad}</span></div>
                            <div class="text-xs text-gray-700"><span class="font-bold">Estado:</span> ${micro.status}</div>
                        </div>
                    </div>
                `;
                infoWindow.setContent(infoHTML);
                infoWindow.open(map, marker);
            });
        });
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCX0SeguX9YMrSGpiiOwwe3eU-ei5YhL4&loading=async&libraries=marker&callback=initMapAdmin" async defer></script>
@endsection
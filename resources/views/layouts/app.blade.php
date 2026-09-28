<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Micros Culiprán')</title>

    <!-- Integración de Tailwind y JS mediante Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-fondo font-sans antialiased text-texto min-h-screen flex flex-col">

    <!-- Navegación Base Adaptable -->
    <nav class="bg-primario shadow-md relative z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Icono de Menú (Hamburguesa) -->
                <button onclick="toggleMenu()" class="text-secundario hover:text-gray-200 focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Logotipo Central -->
                <div class="flex flex-col items-center justify-center">
                    <span class="text-secundario font-bold text-lg leading-tight">Micros</span>
                    <span class="text-secundario font-extrabold text-xl leading-none tracking-widest uppercase">Culiprán</span>
                </div>

                <!-- Icono de Notificaciones -->
                <a href="/notificaciones" class="relative text-secundario hover:text-gray-200 focus:outline-none active:scale-95 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-peligro ring-2 ring-primario"></span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal Dinámico -->
    <main class="flex-grow max-w-7xl mx-auto w-full py-6 px-4 sm:px-6 lg:px-8 relative z-10">
        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="bg-secundario shadow-inner mt-auto py-4 border-t border-gray-200">
        <div class="text-center text-sm text-texto/70">
            &copy; {{ date('Y') }} Sistema Operativo Micros Culiprán. Todos los derechos reservados.
        </div>
    </footer>

    <!-- Menú Lateral (Off-Canvas) Dinámico -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-texto/50 z-40 hidden transition-opacity backdrop-blur-sm" onclick="toggleMenu()"></div>
    <aside id="sidebarMenu" class="fixed top-0 left-0 w-72 h-full bg-secundario shadow-2xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col">

        <div class="bg-primario p-5 flex justify-between items-center shadow-md">
            <span class="text-secundario font-extrabold text-xl tracking-widest uppercase">Sistema Central</span>
            <button onclick="toggleMenu()" class="text-secundario hover:text-gray-200 bg-primario-dark p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Panel de Identificación del Usuario -->
        @auth
        <div class="bg-fondo p-5 border-b border-gray-200 flex flex-col">
            <span class="text-[10px] font-bold text-texto/50 uppercase tracking-widest mb-1">Sesión Activa</span>
            <span class="font-black text-texto text-lg leading-tight">{{ Auth::user()->nombre }}</span>
            <span class="text-xs font-bold text-primario uppercase tracking-widest mt-0.5">{{ Auth::user()->rol }}</span>
        </div>
        @endauth

        <div class="p-4 flex flex-col space-y-2 flex-grow overflow-y-auto">

            <!-- Enlace Público (Portal Pasajeros) -->
            <a href="/" class="p-4 rounded-2xl font-bold transition-all flex items-center border 
            {{ Request::is('/') || Request::is('mapa') || Request::is('horarios') || Request::is('rutas') || Request::is('tarifas') || Request::is('tiempos') || Request::is('notificaciones*') 
                ? 'text-primario bg-primario/10 border-primario/20' 
                : 'text-texto hover:bg-fondo border-transparent hover:border-gray-100' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::is('/') || Request::is('mapa') || Request::is('horarios') || Request::is('rutas') || Request::is('tarifas') || Request::is('tiempos') || Request::is('notificaciones*') ? 'text-primario' : 'text-texto/50' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Portal Pasajeros
            </a>

            @auth
            <!-- Enlaces exclusivos para Conductores -->
            @if(Auth::user()->rol === 'conductor')
            <a href="/conductor" class="p-4 rounded-2xl font-bold transition-all flex items-center border 
            {{ Request::is('conductor*') 
                ? 'text-primario bg-primario/10 border-primario/20' 
                : 'text-texto hover:bg-fondo border-transparent hover:border-gray-100' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::is('conductor*') ? 'text-primario' : 'text-texto/50' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Mi Panel Operativo
            </a>
            @endif

            <!-- Enlaces exclusivos para Administradores -->
            @if(Auth::user()->rol === 'administrador')
            <a href="/admin" class="p-4 rounded-2xl font-bold transition-all flex items-center border 
            {{ Request::is('admin*') 
                ? 'text-primario bg-primario/10 border-primario/20' 
                : 'text-texto hover:bg-fondo border-transparent hover:border-gray-100' }}">
                <svg class="w-5 h-5 mr-3 {{ Request::is('admin*') ? 'text-primario' : 'text-texto/50' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Dashboard Administrativo
            </a>
            @endif
            @endauth

            <!-- Enlace para ingresar (Sólo si NO ha iniciado sesión) -->
            @guest
            <a href="/login" class="p-4 rounded-2xl font-bold transition-all flex items-center border 
            {{ Request::is('login') 
                ? 'text-primario bg-primario/10 border-primario/20' 
                : 'text-texto hover:bg-fondo border-transparent hover:border-gray-100' }} mt-4">
                <svg class="w-5 h-5 mr-3 {{ Request::is('login') ? 'text-primario' : 'text-texto/50' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Acceso Personal
            </a>
            @endguest

        </div>

        <!-- Área Inferior: Cierre de Sesión o Branding -->
        <div class="p-4 border-t border-gray-100 bg-fondo">
            @auth
            <!-- Formulario seguro para cerrar sesión -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full p-4 rounded-2xl font-black text-peligro bg-peligro/10 hover:bg-peligro/20 active:scale-95 transition-all flex items-center justify-center border border-peligro/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    CERRAR SESIÓN
                </button>
            </form>
            @else
            <p class="text-[10px] text-texto/40 text-center font-black tracking-widest uppercase">Micros Culiprán v1.0</p>
            @endauth
        </div>
    </aside>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>

</body>

</html>
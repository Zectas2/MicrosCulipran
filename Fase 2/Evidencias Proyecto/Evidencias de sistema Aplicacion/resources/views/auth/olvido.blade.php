<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Recuperar Clave - Micros Culiprán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-fondo min-h-screen flex font-sans text-texto relative">

    <!-- Fondo Móvil Decorativo (Estilo App Nativa) -->
    <div class="absolute top-0 left-0 w-full h-72 bg-primario rounded-b-[3rem] z-0 lg:hidden shadow-inner">
        <div class="absolute top-[-20%] left-[-10%] w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Panel Izquierdo Desktop (Consistencia Visual) -->
    <div class="hidden lg:flex lg:w-1/2 bg-primario relative overflow-hidden flex-col justify-center items-center p-12 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-secundario/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-acento/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 text-center text-secundario">
            <svg class="w-24 h-24 mx-auto mb-6 text-secundario drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
            <h1 class="text-5xl font-black tracking-tight mb-2">Seguridad Central</h1>
            <p class="text-lg font-medium text-secundario/80 tracking-widest uppercase">Protocolo de Recuperación</p>
        </div>
    </div>

    <!-- Contenedor del Formulario -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-5 sm:p-12 relative z-10">

        <!-- Tarjeta Flotante Optimizada -->
        <div class="w-full max-w-md bg-secundario p-8 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-primario/20 border border-gray-50 mt-16 lg:mt-0">

            <!-- Icono flotante superior (Solo Móvil) -->
            <div class="lg:hidden flex justify-center -mt-16 mb-4 relative z-20">
                <div class="bg-secundario p-4 rounded-3xl shadow-lg border border-gray-100 text-primario">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
            </div>

            <!-- Encabezados -->
            <div class="text-center mb-8 lg:hidden">
                <h2 class="text-2xl font-black text-texto tracking-tight">Recuperar Acceso</h2>
                <p class="text-[10px] font-bold text-primario uppercase tracking-widest mt-1">Soporte Operativo</p>
            </div>

            <div class="mb-8 hidden lg:block text-center">
                <h2 class="text-3xl font-black text-texto">Recuperación</h2>
                <p class="text-sm font-bold text-texto/50 mt-1 leading-relaxed">Ingrese su correo corporativo para notificar a la central.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-2xl flex items-start animate-fade-in-down">
                    <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-red-700 font-bold leading-tight">{{ $errors->first() }}</p>
                </div>
                @endif

                <!-- Email con Icono Integrado -->
                <div>
                    <label class="block text-[10px] font-bold text-texto/60 uppercase tracking-widest mb-1.5 ml-1">Correo Corporativo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-texto/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="usuario@microsculipran.cl"
                            class="w-full bg-fondo/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 text-texto text-sm font-bold focus:outline-none focus:border-primario focus:ring-2 focus:ring-primario/20 transition-all shadow-inner" required autofocus>
                    </div>
                </div>

                <button type="submit" class="w-full bg-texto text-secundario font-black text-base py-4 rounded-2xl shadow-lg active:scale-95 transition-all hover:bg-gray-800 flex justify-center items-center space-x-2">
                    <span>Notificar a Central</span>
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-[11px] font-bold text-texto/50 hover:text-primario transition-colors uppercase tracking-widest flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Inicio
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
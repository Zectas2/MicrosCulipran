<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Acceso - Micros Culiprán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-fondo min-h-screen flex font-sans text-texto relative">

    <!-- Fondo Móvil Decorativo (Estilo App Nativa) -->
    <div class="absolute top-0 left-0 w-full h-72 bg-primario rounded-b-[3rem] z-0 lg:hidden shadow-inner">
        <!-- Círculos decorativos -->
        <div class="absolute top-[-20%] left-[-10%] w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Panel Izquierdo Desktop (Mantenemos la elegancia) -->
    <div class="hidden lg:flex lg:w-1/2 bg-primario relative overflow-hidden flex-col justify-center items-center p-12 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-secundario/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-acento/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 text-center text-secundario">
            <svg class="w-24 h-24 mx-auto mb-6 text-secundario drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h1 class="text-5xl font-black tracking-tight mb-2">Micros Culiprán</h1>
            <p class="text-lg font-medium text-secundario/80 tracking-widest uppercase">Sistema de Control Operativo</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Encabezados -->
            <div class="text-center mb-8 lg:hidden">
                <h2 class="text-3xl font-black text-texto tracking-tight">Micros Culiprán</h2>
                <p class="text-[10px] font-bold text-primario uppercase tracking-widest mt-1">Control Operativo</p>
            </div>

            <div class="mb-8 hidden lg:block text-center">
                <h2 class="text-3xl font-black text-texto">Bienvenido</h2>
                <p class="text-sm font-bold text-texto/50 mt-1">Ingrese sus credenciales corporativas</p>
            </div>

            <!-- Formulario Funcional -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Alertas del Sistema -->
                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-2xl flex items-start animate-fade-in-down">
                    <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-red-700 font-bold leading-tight">{{ $errors->first() }}</p>
                </div>
                @endif

                @if (session('exito'))
                <div class="bg-green-50 border border-green-200 p-4 rounded-2xl flex items-start animate-fade-in-down">
                    <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-700 font-bold leading-tight">{{ session('exito') }}</p>
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

                <!-- Password con Icono Integrado -->
                <div>
                    <label class="block text-[10px] font-bold text-texto/60 uppercase tracking-widest mb-1.5 ml-1">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-texto/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full bg-fondo/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-4 text-texto text-sm font-bold focus:outline-none focus:border-primario focus:ring-2 focus:ring-primario/20 transition-all shadow-inner" required>
                    </div>
                </div>

                <!-- Toggles y Enlaces -->
                <div class="flex items-center justify-between pt-2 px-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-primario focus:ring-primario w-4 h-4 transition-colors cursor-pointer">
                        <span class="ml-2 text-[11px] text-texto/60 font-bold group-hover:text-texto transition-colors">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[11px] text-primario font-black hover:text-primario-dark transition-colors">¿Olvidó su clave?</a>
                </div>

                <!-- Botón Principal -->
                <button type="submit" class="w-full bg-primario text-secundario font-black text-lg py-4 rounded-2xl shadow-lg shadow-primario/30 active:scale-95 transition-all flex justify-center items-center space-x-2 hover:bg-gray-800 mt-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Ingresar al Sistema</span>
                </button>
            </form>
        </div>
    </div>
</body>

</html>
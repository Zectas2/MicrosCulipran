<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Micros Culiprán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-fondo min-h-screen flex font-sans text-texto">

    <!-- Panel Izquierdo: Branding y Decoración (Oculto en móviles) -->
    <div class="hidden lg:flex lg:w-1/2 bg-primario relative overflow-hidden flex-col justify-center items-center p-12">
        <!-- Círculos decorativos de fondo -->
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

    <!-- Panel Derecho: Formulario de Acceso -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
        <div class="w-full max-w-md bg-secundario p-8 sm:p-10 rounded-3xl shadow-2xl border border-gray-100 relative z-10">

            <!-- Encabezado Móvil -->
            <div class="text-center mb-8 lg:hidden">
                <h2 class="text-3xl font-black text-texto">Micros Culiprán</h2>
                <p class="text-xs font-bold text-primario uppercase tracking-widest mt-1">Control Operativo</p>
            </div>

            <div class="mb-8 hidden lg:block">
                <h2 class="text-3xl font-black text-texto">Bienvenido</h2>
                <p class="text-sm font-bold text-texto/50 mt-1">Ingrese sus credenciales corporativas</p>
            </div>

            <!-- Formulario Funcional -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
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

                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-widest mb-2">Correo Corporativo</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="usuario@microsculipran.cl"
                        class="w-full bg-fondo border border-gray-200 rounded-xl px-4 py-3.5 text-texto font-bold focus:outline-none focus:border-primario focus:ring-2 focus:ring-primario/20 transition-all shadow-inner" required autofocus>
                </div>

                <div>
                    <label class="block text-xs font-bold text-texto/70 uppercase tracking-widest mb-2">Contraseña de Acceso</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full bg-fondo border border-gray-200 rounded-xl px-4 py-3.5 text-texto font-bold focus:outline-none focus:border-primario focus:ring-2 focus:ring-primario/20 transition-all shadow-inner" required>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-primario focus:ring-primario w-4 h-4 transition-colors cursor-pointer">
                        <span class="ml-2 text-xs text-texto/60 font-bold group-hover:text-texto transition-colors">Mantener sesión activa</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs text-primario font-black hover:text-primario-dark transition-colors">¿Olvidó su clave?</a>
                </div>

                <button type="submit" class="w-full bg-primario text-secundario font-black text-lg py-4 rounded-xl shadow-md active:scale-95 transition-all flex justify-center items-center space-x-2 hover:bg-gray-800 mt-2">
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
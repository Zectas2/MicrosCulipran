<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Clave - Micros Culiprán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-fondo min-h-screen flex items-center justify-center p-4 font-sans text-texto relative overflow-hidden">

    <!-- Decoración de fondo -->
    <div class="absolute top-[-20%] left-[-10%] w-96 h-96 bg-primario/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md bg-secundario p-8 sm:p-10 rounded-3xl shadow-2xl border border-gray-100 relative z-10">

        <div class="text-center mb-8">
            <div class="bg-primario/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primario" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-texto">Recuperar Acceso</h2>
            <p class="text-xs font-bold text-texto/50 mt-2 leading-relaxed">Ingrese su correo corporativo. El sistema notificará a la central para la regeneración de su credencial.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 p-4 rounded-2xl flex items-start">
                <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm text-red-700 font-bold">{{ $errors->first() }}</p>
            </div>
            @endif

            <div>
                <label class="block text-[10px] font-bold text-texto/70 uppercase tracking-widest mb-2">Correo Corporativo</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="usuario@microsculipran.cl"
                    class="w-full bg-fondo border border-gray-200 rounded-xl px-4 py-3.5 text-texto font-bold focus:outline-none focus:border-primario focus:ring-2 focus:ring-primario/20 transition-all shadow-inner" required autofocus>
            </div>

            <button type="submit" class="w-full bg-texto text-secundario font-black text-base py-4 rounded-xl shadow-md active:scale-95 transition-all hover:bg-gray-800">
                Solicitar Nueva Clave a Central
            </button>

            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-xs font-bold text-texto/50 hover:text-primario transition-colors uppercase tracking-widest flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Inicio
                </a>
            </div>
        </form>
    </div>
</body>

</html>
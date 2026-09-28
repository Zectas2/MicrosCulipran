<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Micros Culiprán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-fondo min-h-screen flex items-center justify-center p-4 font-sans text-texto">

    <div class="bg-secundario w-full max-w-sm rounded-2xl shadow-sm border border-gray-200 p-8">

        <!-- Encabezado Simple -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-texto">Micros Culiprán</h1>
            <p class="text-xs font-bold text-primario uppercase tracking-widest mt-1">Control Operativo</p>
        </div>

        <!-- Formulario Simple -->
        <form method="POST" action="/login" class="space-y-5">
            <!-- Directiva real de seguridad CSRF de Laravel -->
            @csrf

            <!-- Alerta de Errores Generales (Ej: Credenciales inválidas) -->
            @if ($errors->any())
            <div class="bg-peligro/10 border-l-4 border-peligro p-4 mb-4 rounded-r-lg flex items-center">
                <svg class="h-5 w-5 text-peligro mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm text-peligro font-bold">
                    {{ $errors->first() }}
                </p>
            </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-texto/80 uppercase tracking-wide mb-2">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@microsculipran.cl"
                    class="w-full bg-fondo border @error('email') border-peligro @else border-gray-300 @enderror rounded-xl px-4 py-3 text-texto focus:outline-none focus:border-primario focus:ring-1 focus:ring-primario" required autofocus>
            </div>

            <div>
                <label class="block text-xs font-bold text-texto/80 uppercase tracking-wide mb-2">Contraseña</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full bg-fondo border @error('password') border-peligro @else border-gray-300 @enderror rounded-xl px-4 py-3 text-texto focus:outline-none focus:border-primario focus:ring-1 focus:ring-primario" required>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primario focus:ring-primario w-4 h-4">
                    <span class="ml-2 text-xs text-texto/80 font-bold">Recordarme</span>
                </label>
                <a href="#" class="text-xs text-primario font-bold hover:underline">¿Olvidó su clave?</a>
            </div>

            <button type="submit" class="w-full bg-primario text-secundario font-black text-lg py-3 rounded-xl shadow-sm hover:opacity-90 transition-opacity mt-4 uppercase tracking-wide">
                Ingresar
            </button>
        </form>

    </div>

</body>

</html>
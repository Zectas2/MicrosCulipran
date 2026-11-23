# Usamos una imagen oficial de PHP 8.3 con FPM y Nginx Alpine
FROM serversideup/php:8.3-fpm-nginx

# Cambiamos al usuario root temporalmente para instalar dependencias de Node.js (Vite) y PostgreSQL
USER root
RUN apt-get update && apt-get install -y nodejs npm postgresql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Volvemos al usuario web para mayor seguridad
USER www-data

# Copiamos los archivos del proyecto
COPY --chown=www-data:www-data . /var/www/html

# Instalamos dependencias de PHP y Node.js, y compilamos los assets
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN rm -rf node_modules
RUN npm install
RUN npx vite build

# Limpiamos caché de configuración de Laravel
RUN php artisan config:clear
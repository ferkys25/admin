#!/bin/bash
set -e

# Limpiar cache anterior
php artisan config:clear || true

# Ejecutar migraciones y seeders de forma automatica en Render
php artisan migrate --force --seed || true

# Configurar Apache para usar el puerto dinamico de Render ($PORT)
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf

# Iniciar Apache
exec apache2-foreground

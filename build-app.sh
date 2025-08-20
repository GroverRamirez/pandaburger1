#!/usr/bin/env bash
set -e

# Instala dependencias de Laravel
composer install --no-dev --optimize-autoloader

# Compila assets si tienes Vite
if [ -f package.json ]; then
  npm ci || npm install
  npm run build || true
fi

# Cachea configuración y rutas (no toca DB)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enlaza storage
php artisan storage:link || true

echo "[build-app] OK"


#!/usr/bin/env bash
set -euo pipefail

# Frontend (si usas Vite)
if [ -f package.json ]; then
  npm ci
  npm run build
fi

# PHP deps sin dev y optimizado
composer install --no-dev --prefer-dist --optimize-autoloader

# Limpiar y cachear LO QUE NO requiere DB
php artisan route:clear || true
php artisan config:clear || true
php artisan view:clear || true
php artisan event:clear || true

php artisan optimize
php artisan config:cache
php artisan view:cache

# Importante: NO migrate, NO seed, NO esperar MySQL aquí
# Importante: NO route:cache aquí hasta que no haya duplicados de nombres



#!/bin/bash
set -e

# — 0) Si falla npm ci (no hay package-lock), usa npm install
if [ -f package-lock.json ]; then
  npm ci
else
  npm install
fi

# — 1) Compila assets (Vite)
npm run build

# — 2) Optimizaciones de Laravel
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# — 3) Enlace de storage (idempotente)
php artisan storage:link || true



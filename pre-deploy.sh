#!/bin/bash
set -e

echo "🚀 Ejecutando pre-deploy de PandaBurger..."

# — 1) Migrar base de datos
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# — 2) Crear enlace de storage
echo "🔗 Creando enlace de storage..."
php artisan storage:link || true

# — 3) Limpiar cache de aplicación (con base de datos disponible)
echo "🧹 Limpiando cache de aplicación..."
php artisan optimize:clear || true

# — 4) Optimizar aplicación
echo "⚡ Optimizando aplicación..."
php artisan optimize || true

echo "✅ Pre-deploy completado exitosamente!"

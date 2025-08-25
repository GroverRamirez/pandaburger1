#!/bin/bash
set -e

echo "🚀 Iniciando build de PandaBurger..."

# — 0) Verificar que estamos en el directorio correcto
if [ ! -f "composer.json" ]; then
    echo "❌ Error: composer.json no encontrado. Asegúrate de estar en el directorio raíz del proyecto."
    exit 1
fi

# — 1) Instalar dependencias de PHP
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# — 2) Instalar dependencias de Node.js
echo "📦 Instalando dependencias de Node.js..."
if [ -f package-lock.json ]; then
    npm ci --production
else
    npm install --production
fi

# — 3) Compilar assets (Vite)
echo "🔨 Compilando assets con Vite..."
npm run build

# — 4) Limpiar cache de Laravel (sin base de datos)
echo "🧹 Limpiando cache de Laravel..."
php artisan config:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan event:clear || true

# — 5) Optimizaciones de Laravel (sin base de datos)
echo "⚡ Optimizando Laravel..."
php artisan config:cache || true
php artisan view:cache || true
php artisan route:cache || true
php artisan event:cache || true

# — 6) Verificar que la aplicación puede iniciar
echo "✅ Verificando que la aplicación puede iniciar..."
php artisan --version

echo "🎉 Build completado exitosamente!"



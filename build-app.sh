#!/bin/bash

# 🚀 Script de Build para Railway - PandaBurger
# Este script prepara la aplicación Laravel + Vue.js para producción

set -e  # Salir si hay algún error

echo "🔧 Iniciando build de la aplicación..."

# 1. Instalar dependencias de PHP
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Instalar dependencias de Node.js
echo "📦 Instalando dependencias de Node.js..."
npm ci --production=false

# 3. Compilar assets con Vite
echo "🎨 Compilando assets con Vite..."
npm run build

# 4. Limpiar cachés de Laravel
echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Optimizar para producción
echo "⚡ Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Configurar permisos
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 7. Crear symlink de storage si no existe
echo "🔗 Creando symlink de storage..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

# 8. Verificar que la aplicación esté lista
echo "✅ Verificando configuración..."
php artisan about

echo "🎉 Build completado exitosamente!"
echo "📋 Resumen:"
echo "   - Dependencias PHP instaladas"
echo "   - Dependencias Node.js instaladas"
echo "   - Assets compilados con Vite"
echo "   - Cachés optimizados"
echo "   - Permisos configurados"
echo "   - Storage link creado"

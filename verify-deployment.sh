#!/bin/bash

# 🔍 Script de Verificación para Railway - PandaBurger
# Este script verifica que todos los archivos necesarios estén presentes

echo "🔍 Verificando archivos para despliegue en Railway..."

# Lista de archivos requeridos
required_files=(
    "README_DEPLOY.md"
    "README_RAILWAY_PACK.md"
    "build-app.sh"
    "run-cron.sh"
    "run-worker.sh"
    "env.railway.example"
    "railway.json"
    "setup-permissions.sh"
    "verify-deployment.sh"
)

# Lista de archivos de configuración del proyecto
project_files=(
    "composer.json"
    "package.json"
    "vite.config.ts"
    "artisan"
    ".env.example"
)

# Verificar archivos requeridos
echo "📋 Verificando archivos del paquete Railway..."
missing_files=()

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file - PRESENTE"
    else
        echo "❌ $file - FALTANTE"
        missing_files+=("$file")
    fi
done

# Verificar archivos del proyecto
echo ""
echo "📋 Verificando archivos del proyecto..."
for file in "${project_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file - PRESENTE"
    else
        echo "❌ $file - FALTANTE"
        missing_files+=("$file")
    fi
done

# Verificar permisos de scripts
echo ""
echo "🔐 Verificando permisos de scripts..."
scripts=("build-app.sh" "run-cron.sh" "run-worker.sh" "setup-permissions.sh" "verify-deployment.sh")

for script in "${scripts[@]}"; do
    if [ -x "$script" ]; then
        echo "✅ $script - PERMISOS OK"
    else
        echo "⚠️  $script - SIN PERMISOS DE EJECUCIÓN"
        echo "   Ejecuta: chmod +x $script"
    fi
done

# Resumen
echo ""
echo "📊 RESUMEN DE VERIFICACIÓN"
echo "=========================="

if [ ${#missing_files[@]} -eq 0 ]; then
    echo "🎉 ¡Todos los archivos requeridos están presentes!"
    echo ""
    echo "🚀 Próximos pasos:"
    echo "1. Ejecuta: chmod +x *.sh"
    echo "2. Haz commit y push:"
    echo "   git add ."
    echo "   git commit -m 'Add Railway deployment package'"
    echo "   git push origin main"
    echo "3. Sigue la guía en README_RAILWAY_PACK.md"
else
    echo "❌ Archivos faltantes:"
    for file in "${missing_files[@]}"; do
        echo "   - $file"
    done
    echo ""
    echo "⚠️  Por favor, asegúrate de que todos los archivos estén presentes antes de continuar."
fi

echo ""
echo "📖 Para más información, consulta:"
echo "   - README_RAILWAY_PACK.md (guía rápida)"
echo "   - README_DEPLOY.md (guía completa)"

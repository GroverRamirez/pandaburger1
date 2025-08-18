#!/bin/bash

# 🔐 Script para configurar permisos de ejecución
# Ejecuta este script localmente antes de hacer push

echo "🔐 Configurando permisos de ejecución..."

# Dar permisos de ejecución a todos los scripts
chmod +x build-app.sh
chmod +x run-cron.sh
chmod +x run-worker.sh
chmod +x setup-permissions.sh

echo "✅ Permisos configurados correctamente"
echo "📋 Scripts con permisos de ejecución:"
echo "   - build-app.sh"
echo "   - run-cron.sh"
echo "   - run-worker.sh"
echo "   - setup-permissions.sh"

echo "🚀 Listo para hacer commit y push a GitHub"

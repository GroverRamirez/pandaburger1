#!/bin/bash

# 🔄 Script de Worker para Railway - PandaBurger
# Este script ejecuta los workers de colas de Laravel

echo "🔄 Iniciando worker de colas..."

# Función para manejar señales de terminación
cleanup() {
    echo "🛑 Deteniendo worker de colas..."
    exit 0
}

# Capturar señales de terminación
trap cleanup SIGTERM SIGINT

# Configurar variables por defecto si no están definidas
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}
QUEUE_TIMEOUT=${QUEUE_TIMEOUT:-60}
QUEUE_TRIES=${QUEUE_TRIES:-3}
QUEUE_MAX_JOBS=${QUEUE_MAX_JOBS:-1000}
QUEUE_MEMORY=${QUEUE_MEMORY:-128}

echo "📋 Configuración del worker:"
echo "   - Conexión: $QUEUE_CONNECTION"
echo "   - Timeout: $QUEUE_TIMEOUT segundos"
echo "   - Intentos: $QUEUE_TRIES"
echo "   - Max jobs: $QUEUE_MAX_JOBS"
echo "   - Memoria: $QUEUE_MEMORY MB"

# Ejecutar el worker con configuración optimizada
php artisan queue:work \
    --queue=default \
    --timeout=$QUEUE_TIMEOUT \
    --tries=$QUEUE_TRIES \
    --max-jobs=$QUEUE_MAX_JOBS \
    --memory=$QUEUE_MEMORY \
    --sleep=3 \
    --verbose

#!/bin/bash

# 🕐 Script de Cron para Railway - PandaBurger
# Este script ejecuta las tareas programadas de Laravel cada 60 segundos

echo "🕐 Iniciando servicio de tareas programadas..."

# Función para ejecutar el scheduler
run_scheduler() {
    echo "⚡ Ejecutando tareas programadas..."
    php artisan schedule:run
    echo "✅ Tareas programadas completadas"
}

# Función para manejar señales de terminación
cleanup() {
    echo "🛑 Deteniendo servicio de tareas programadas..."
    exit 0
}

# Capturar señales de terminación
trap cleanup SIGTERM SIGINT

# Bucle principal - ejecutar cada 60 segundos
while true; do
    run_scheduler
    echo "⏳ Esperando 60 segundos hasta la próxima ejecución..."
    sleep 60
done

<?php

echo "🔍 Verificando variables de entorno...\n\n";

// Variables críticas para Railway
$criticalVars = [
    'APP_NAME',
    'APP_ENV',
    'APP_KEY',
    'APP_DEBUG',
    'APP_URL',
    'DB_CONNECTION',
    'DATABASE_URL',
    'CACHE_DRIVER',
    'SESSION_DRIVER',
    'QUEUE_CONNECTION'
];

foreach ($criticalVars as $var) {
    $value = getenv($var);
    if ($value === false) {
        echo "❌ $var: NO CONFIGURADA\n";
    } else {
        // Ocultar valores sensibles
        if (in_array($var, ['APP_KEY', 'DATABASE_URL'])) {
            $displayValue = substr($value, 0, 20) . '...';
        } else {
            $displayValue = $value;
        }
        echo "✅ $var: $displayValue\n";
    }
}

echo "\n🔍 Verificando configuración de base de datos...\n";

// Verificar configuración de base de datos
$dbConnection = getenv('DB_CONNECTION');
$databaseUrl = getenv('DATABASE_URL');

if ($dbConnection !== 'mysql') {
    echo "⚠️  DB_CONNECTION no está configurado como 'mysql'\n";
}

if (!$databaseUrl) {
    echo "❌ DATABASE_URL no está configurada\n";
} else {
    echo "✅ DATABASE_URL está configurada\n";
}

echo "\n🔍 Verificando configuración de Laravel...\n";

// Verificar configuración de Laravel
$appEnv = getenv('APP_ENV');
$appDebug = getenv('APP_DEBUG');

if ($appEnv !== 'production') {
    echo "⚠️  APP_ENV no está configurado como 'production'\n";
}

if ($appDebug !== 'false') {
    echo "⚠️  APP_DEBUG no está configurado como 'false'\n";
}

echo "\n✅ Verificación completada.\n";

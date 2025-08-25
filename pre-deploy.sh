#!/bin/bash
set -e

echo "🚀 Ejecutando pre-deploy de PandaBurger..."

# — 1) Esperar a que MySQL esté disponible
echo "⏳ Esperando a que MySQL esté disponible..."
php -r '
$maxAttempts = 30;
$attempt = 0;

while ($attempt < $maxAttempts) {
    try {
        $dsn = getenv("DATABASE_URL");
        if (!$dsn) {
            throw new Exception("DATABASE_URL no está configurada");
        }
        
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->query("SELECT 1");
        echo "✅ MySQL está disponible\n";
        exit(0);
    } catch (Exception $e) {
        $attempt++;
        echo "⏳ Intento $attempt/$maxAttempts - MySQL no disponible: " . $e->getMessage() . "\n";
        if ($attempt >= $maxAttempts) {
            echo "❌ Timeout esperando MySQL\n";
            exit(1);
        }
        sleep(2);
    }
}
'

# — 2) Migrar base de datos
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# — 3) Crear enlace de storage
echo "🔗 Creando enlace de storage..."
php artisan storage:link || true

# — 4) Limpiar cache de aplicación (con base de datos disponible)
echo "🧹 Limpiando cache de aplicación..."
php artisan optimize:clear || true

# — 5) Optimizar aplicación
echo "⚡ Optimizando aplicación..."
php artisan optimize || true

echo "✅ Pre-deploy completado exitosamente!"

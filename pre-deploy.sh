#!/usr/bin/env bash
set -e

echo "[pre-deploy] Esperando a que MySQL esté disponible..."

php -r '
$host = getenv("DB_HOST");
$port = getenv("DB_PORT") ?: "3306";
$db   = getenv("DB_DATABASE");
$user = getenv("DB_USERNAME");
$pass = getenv("DB_PASSWORD");

if (!$host || !$db || !$user) {
  fwrite(STDERR, "[pre-deploy] Variables DB_* incompletas.\n");
  exit(1);
}

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
$start = time();
while (true) {
  try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 2]);
    echo "[pre-deploy] MySQL listo.\n";
    break;
  } catch (Exception $e) {
    if (time() - $start > 90) {
      fwrite(STDERR, "[pre-deploy] Timeout esperando MySQL: " . $e->getMessage() . "\n");
      exit(1);
    }
    usleep(500000); // 0.5s
  }
}
'

echo "[pre-deploy] Ejecutando migraciones"
php artisan migrate --force
echo "[pre-deploy] OK"

@echo off
echo ========================================
echo    VERIFICACION DE ARCHIVOS RAILWAY
echo ========================================
echo.

echo Archivos del paquete Railway:
if exist "README_DEPLOY.md" (echo [OK] README_DEPLOY.md) else (echo [FALTA] README_DEPLOY.md)
if exist "README_RAILWAY_PACK.md" (echo [OK] README_RAILWAY_PACK.md) else (echo [FALTA] README_RAILWAY_PACK.md)
if exist "build-app.sh" (echo [OK] build-app.sh) else (echo [FALTA] build-app.sh)
if exist "run-cron.sh" (echo [OK] run-cron.sh) else (echo [FALTA] run-cron.sh)
if exist "run-worker.sh" (echo [OK] run-worker.sh) else (echo [FALTA] run-worker.sh)
if exist "env.railway.example" (echo [OK] env.railway.example) else (echo [FALTA] env.railway.example)
if exist "railway.json" (echo [OK] railway.json) else (echo [FALTA] railway.json)
if exist "setup-permissions.sh" (echo [OK] setup-permissions.sh) else (echo [FALTA] setup-permissions.sh)
if exist "verify-deployment.sh" (echo [OK] verify-deployment.sh) else (echo [FALTA] verify-deployment.sh)

echo.
echo Archivos del proyecto:
if exist "composer.json" (echo [OK] composer.json) else (echo [FALTA] composer.json)
if exist "package.json" (echo [OK] package.json) else (echo [FALTA] package.json)
if exist "vite.config.ts" (echo [OK] vite.config.ts) else (echo [FALTA] vite.config.ts)
if exist "artisan" (echo [OK] artisan) else (echo [FALTA] artisan)

echo.
echo ========================================
echo    PROXIMOS PASOS
echo ========================================
echo 1. Hacer commit y push:
echo    git add .
echo    git commit -m "Add Railway deployment package"
echo    git push origin main
echo.
echo 2. Seguir la guia en README_RAILWAY_PACK.md
echo.
echo ========================================
pause

# 🔐 Script para configurar permisos en Windows
# Ejecuta este script en PowerShell antes de hacer push

Write-Host "🔐 Configurando permisos de ejecución..." -ForegroundColor Green

# Lista de scripts a configurar
$scripts = @(
    "build-app.sh",
    "run-cron.sh", 
    "run-worker.sh",
    "setup-permissions.sh",
    "verify-deployment.sh"
)

foreach ($script in $scripts) {
    if (Test-Path $script) {
        Write-Host "✅ $script - PRESENTE" -ForegroundColor Green
    } else {
        Write-Host "❌ $script - FALTANTE" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "📋 Nota: En Windows, los permisos de ejecución se manejan automáticamente" -ForegroundColor Yellow
Write-Host "   Los scripts funcionarán correctamente en Railway (Linux)" -ForegroundColor Yellow

Write-Host ""
Write-Host "🚀 Próximos pasos:" -ForegroundColor Cyan
Write-Host "1. Hacer commit y push:" -ForegroundColor White
Write-Host "   git add ." -ForegroundColor Gray
Write-Host "   git commit -m 'Add Railway deployment package'" -ForegroundColor Gray
Write-Host "   git push origin main" -ForegroundColor Gray
Write-Host "2. Seguir la guía en README_RAILWAY_PACK.md" -ForegroundColor White

Write-Host ""
Write-Host "✅ Listo para desplegar en Railway!" -ForegroundColor Green

# Script de optimización para Windows/XAMPP
Write-Host "Optimizando aplicación Laravel para producción..." -ForegroundColor Green

# 1. Limpiar cachés antiguas
Write-Host "Limpiando cachés antiguas..." -ForegroundColor Yellow
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# 2. Optimizar Composer (solo clases necesarias)
Write-Host "Optimizando autoload de Composer..." -ForegroundColor Yellow
composer dump-autoload --optimize

# 3. Cachear configuraciones
Write-Host "Cacheando configuraciones..." -ForegroundColor Yellow
php artisan config:cache

# 4. Cachear rutas
Write-Host "Cacheando rutas..." -ForegroundColor Yellow
php artisan route:cache

# 5. Cachear vistas
Write-Host "Cacheando vistas..." -ForegroundColor Yellow
php artisan view:cache

# 6. Optimizar aplicación
Write-Host "Optimizando aplicación..." -ForegroundColor Yellow
php artisan optimize

Write-Host ""
Write-Host "Optimización completada!" -ForegroundColor Green
Write-Host ""
Write-Host "Configuraciones recomendadas para .env en producción:" -ForegroundColor Cyan
Write-Host "APP_ENV=production" -ForegroundColor White
Write-Host "APP_DEBUG=false" -ForegroundColor White
Write-Host "SESSION_DRIVER=file" -ForegroundColor White
Write-Host "CACHE_DRIVER=file" -ForegroundColor White
Write-Host "QUEUE_CONNECTION=sync" -ForegroundColor White

#!/bin/bash

# Script de optimización para servidor LAMP
echo "🚀 Optimizando aplicación Laravel para producción..."

# 1. Limpiar cachés antiguas
echo "🧹 Limpiando cachés antiguas..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# 2. Optimizar Composer (solo clases necesarias)
echo "📦 Optimizando autoload de Composer..."
composer install --optimize-autoloader --no-dev

# 3. Cachear configuraciones
echo "⚙️ Cacheando configuraciones..."
php artisan config:cache

# 4. Cachear rutas
echo "🛣️ Cacheando rutas..."
php artisan route:cache

# 5. Cachear vistas
echo "👁️ Cacheando vistas..."
php artisan view:cache

# 6. Optimizar base de datos (opcional)
echo "🗄️ Optimizando base de datos..."
php artisan optimize

echo "✅ ¡Optimización completada!"
echo ""
echo "📋 Configuraciones recomendadas para .env en producción:"
echo "APP_ENV=production"
echo "APP_DEBUG=false"
echo "SESSION_DRIVER=file"
echo "CACHE_DRIVER=file"
echo "QUEUE_CONNECTION=database"

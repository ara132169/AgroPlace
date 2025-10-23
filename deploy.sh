#!/bin/bash

echo "🚀 Iniciando deployment de AgroMarket..."

# Activar modo mantenimiento
php artisan down --message="Actualizando AgroMarket..." --retry=60

# Actualizar código desde Git
echo "📥 Actualizando código..."
git pull origin main

# Instalar dependencias de Composer (optimizado para producción)
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader

# Instalar dependencias de NPM
echo "📦 Instalando dependencias de Node.js..."
npm install

# Compilar assets para producción
echo "🔨 Compilando assets..."
npm run build

# Ejecutar migraciones de base de datos
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y optimizar cache
echo "🧹 Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Generar sitemap (si tienes uno)
# php artisan sitemap:generate

# Establecer permisos correctos
echo "🔐 Configurando permisos..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Desactivar modo mantenimiento
php artisan up

echo "✅ Deployment completado exitosamente!"
echo "🌐 AgroMarket está listo en: $(php artisan env:get APP_URL)"

#!/bin/bash

# Script para verificar el estado de optimizaciones
echo "================================"
echo "  VERIFICACIÓN DE OPTIMIZACIONES"
echo "================================"
echo ""

# Verificar caché
echo "📦 ESTADO DE CACHÉ:"
php artisan about | grep -A 3 "Cache"
echo ""

# Verificar rutas clave
echo "🛣️  RUTAS DEL CARRITO:"
php artisan route:list --path=carrito | grep -E "(actualizar-bulk|actualizar)"
echo ""

# Verificar tamaño de logs
echo "📝 TAMAÑO DE LOGS:"
du -sh storage/logs/
echo ""

# Verificar permisos
echo "🔒 PERMISOS (storage y bootstrap/cache):"
ls -la storage | head -5
ls -la bootstrap/cache | head -3
echo ""

# Verificar archivos de optimización
echo "📄 ARCHIVOS DE OPTIMIZACIÓN:"
if [ -f "optimize.sh" ]; then
    echo "  ✅ optimize.sh encontrado"
else
    echo "  ❌ optimize.sh NO encontrado"
fi

if [ -f "optimize.ps1" ]; then
    echo "  ✅ optimize.ps1 encontrado"
else
    echo "  ❌ optimize.ps1 NO encontrado"
fi

if [ -f "OPTIMIZACION.md" ]; then
    echo "  ✅ OPTIMIZACION.md encontrado"
else
    echo "  ❌ OPTIMIZACION.md NO encontrado"
fi
echo ""

echo "================================"
echo "  ✅ VERIFICACIÓN COMPLETADA"
echo "================================"

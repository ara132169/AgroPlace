# Guía de Optimización para Servidor LAMP

## 📊 Optimizaciones Implementadas

### 1. **Optimización de JavaScript**
- ✅ Reducción de llamadas AJAX múltiples a UNA sola llamada bulk
- ✅ Debounce reducido de 300ms a 150ms
- ✅ Actualización de UI inmediata (sin esperar servidor)
- ✅ Eliminación de logs excesivos en consola

### 2. **Optimización de Backend**
- ✅ Nuevo endpoint `actualizarCarritoBulk` para actualizar todo el carrito de una vez
- ✅ Reducción de logs en producción
- ✅ Caché de configuraciones, rutas y vistas
- ✅ Autoload optimizado de Composer

### 3. **Cachés de Laravel**
```bash
php artisan config:cache    # Cachea configuraciones
php artisan route:cache     # Cachea rutas
php artisan view:cache      # Cachea vistas
composer dump-autoload --optimize  # Optimiza autoload
```

## 🚀 Para Servidor LAMP en Producción

### Configuración de .env
```env
APP_ENV=production
APP_DEBUG=false
APP_LOG_LEVEL=error

# Usar caché de archivos (más rápido en servidor compartido)
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Optimización de sesiones
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

### Configuración de Apache (.htaccess en /public)
```apache
# Ya incluido en Laravel, pero verificar:
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Activar compresión GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Cachear recursos estáticos
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
</IfModule>
```

### Optimización de PHP (php.ini)
```ini
# Aumentar límites si es posible
memory_limit = 256M
max_execution_time = 60
upload_max_filesize = 20M
post_max_size = 20M

# Activar OPcache
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

## 📦 Script de Despliegue

### Para Linux (LAMP)
```bash
chmod +x optimize.sh
./optimize.sh
```

### Para Windows (XAMPP) - Desarrollo
```powershell
powershell -ExecutionPolicy Bypass -File optimize.ps1
```

## 🔧 Comandos Útiles

### Limpiar todo y optimizar desde cero
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload --optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### En desarrollo (después de cambios)
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

## ⚡ Mejoras Adicionales Recomendadas

### 1. Minificar Assets
```bash
npm run build  # Si usas Vite/Mix
```

### 2. Optimizar Imágenes
- Convertir a WebP cuando sea posible
- Usar lazy loading (ya implementado)
- Dimensiones apropiadas (80x80 para thumbnails)

### 3. CDN para Recursos Externos
- jQuery, Bootstrap, etc. desde CDN
- Stripe.js ya viene de CDN ✅

### 4. Base de Datos
```sql
-- Indexar columnas frecuentemente consultadas
ALTER TABLE products ADD INDEX idx_slug (slug);
ALTER TABLE categories ADD INDEX idx_slug (slug);
ALTER TABLE orders ADD INDEX idx_client_id (client_id);
```

### 5. Configuración de MySQL
```ini
# En my.cnf o my.ini
[mysqld]
query_cache_size = 32M
query_cache_type = 1
innodb_buffer_pool_size = 256M
```

## 📈 Monitoreo de Rendimiento

### Herramientas Recomendadas
- **Google PageSpeed Insights**: https://pagespeed.web.dev/
- **GTmetrix**: https://gtmetrix.com/
- **Chrome DevTools**: Network tab, Lighthouse

### Métricas Objetivo
- FCP (First Contentful Paint): < 1.8s
- LCP (Largest Contentful Paint): < 2.5s
- CLS (Cumulative Layout Shift): < 0.1
- TTI (Time to Interactive): < 3.8s

## 🎯 Resultados Esperados

### Antes
- ❌ Múltiples llamadas AJAX (una por producto)
- ❌ Logs excesivos en producción
- ❌ Sin caché de configuraciones
- ❌ Carga lenta (3-5 segundos)

### Después
- ✅ Una sola llamada AJAX bulk
- ✅ Logs mínimos y eficientes
- ✅ Caché completa activada
- ✅ Carga rápida (< 2 segundos)

---

**Última actualización**: 6 de noviembre, 2025
**Versión Laravel**: 10.48.22

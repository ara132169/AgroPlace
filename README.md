# 🌱 AgroMarket

Marketplace digital para la venta y compra de insumos agrícolas.

## 🚀 Características

- **Multi-vendor**: Múltiples vendedores pueden registrar sus tiendas
- **Catálogo de productos**: Sistema completo de categorías y subcategorías
- **Carrito de compras**: Funcionalidad completa de e-commerce
- **Panel de vendedor**: Gestión de productos y pedidos
- **Panel de administración**: Control total del marketplace
- **Optimizado**: Cache, lazy loading y optimización de imágenes
- **Responsive**: Diseño adaptable a todos los dispositivos

## 🛠️ Tecnologías

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Bootstrap 5
- **Base de datos**: MySQL
- **Cache**: Redis (opcional)
- **Pagos**: MercadoPago integration
- **Optimización**: Image optimization, lazy loading

## 📋 Requisitos del servidor

- PHP >= 8.1
- Composer
- Node.js >= 16
- MySQL >= 5.7
- Apache/Nginx
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD

## 🚀 Instalación

### 1. Clonar repositorio
```bash
git clone https://github.com/tu-usuario/agromarket.git
cd agromarket
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos
Edita `.env` con tus datos de BD:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agromarket
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 5. Ejecutar migraciones
```bash
php artisan migrate --seed
```

### 6. Compilar assets
```bash
npm run build
```

### 7. Configurar permisos
```bash
chmod -R 775 storage bootstrap/cache
```

### 8. Crear directorios de imágenes
```bash
mkdir -p public/images/{products,shops,sellers}
chmod -R 775 public/images
```

## 🌐 Deployment

### Deployment automático
```bash
chmod +x deploy.sh
./deploy.sh
```

### Deployment manual
1. Subir archivos al servidor
2. Configurar `.env` para producción
3. Ejecutar: `composer install --no-dev --optimize-autoloader`
4. Ejecutar: `npm run build`
5. Ejecutar: `php artisan migrate --force`
6. Ejecutar: `php artisan optimize`

## 🔧 Configuración del servidor web

### Apache (.htaccess ya incluido)
```apache
<VirtualHost *:80>
    DocumentRoot /path/to/agromarket/public
    ServerName tu-dominio.com
    
    <Directory /path/to/agromarket/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /path/to/agromarket/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 📁 Estructura del proyecto

```
agromarket/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── ...
├── resources/
│   ├── views/
│   ├── js/
│   └── css/
├── public/
│   ├── images/
│   ├── front/assets/
│   └── index.php
├── database/
│   ├── migrations/
│   └── seeders/
└── routes/
    └── web.php
```

## 🔐 Seguridad

- Todas las contraseñas están hasheadas
- Protección CSRF habilitada
- Validación de archivos de imagen
- Sanitización de inputs
- Headers de seguridad configurados

## 📞 Soporte

Para soporte técnico o consultas sobre el proyecto, contacta:
- Email: soporte@agromarket.com
- Issues: [GitHub Issues](https://github.com/tu-usuario/agromarket/issues)

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

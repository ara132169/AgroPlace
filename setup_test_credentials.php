<?php

/*
 * Script para crear/actualizar credenciales de vendedor para pruebas
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔑 CONFIGURANDO CREDENCIALES DE VENDEDOR PARA PRUEBA\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    $seller = App\Models\Seller::first();
    
    if ($seller) {
        // Actualizar con credenciales conocidas
        $seller->update([
            'email' => 'vendedor@test.com',
            'password' => bcrypt('password123'), // Contraseña simple para prueba
            'verified' => 1 // Asegurar que esté verificado
        ]);
        
        echo "✅ Credenciales actualizadas para: {$seller->name}\n";
        echo "📧 Email: vendedor@test.com\n";
        echo "🔒 Password: password123\n\n";
        
        // Verificar que el vendedor tenga una tienda
        $shop = $seller->shop;
        if (!$shop) {
            // Crear tienda si no existe
            App\Models\Shop::create([
                'seller_id' => $seller->id,
                'shop_name' => $seller->name . ' Store',
                'shop_email' => $seller->email,
                'shop_phone' => '1234567890',
                'shop_address' => 'Dirección de prueba',
                'shop_description' => 'Tienda de prueba para sistema de comisiones'
            ]);
            echo "🏪 Tienda creada para el vendedor\n";
        } else {
            echo "🏪 Tienda existente: {$shop->shop_name}\n";
        }
        
        echo "\n🚀 INSTRUCCIONES PARA PROBAR:\n";
        echo "1. Ir a: http://127.0.0.1:8000/tienda/ingresar\n";
        echo "2. Login con:\n";
        echo "   Email: vendedor@test.com\n";
        echo "   Password: password123\n";
        echo "3. Una vez logueado, ir al menú '💳 Configurar Pagos'\n";
        echo "4. Ir a 'Ventas' para ver las comisiones\n\n";
        
    } else {
        echo "❌ No se encontraron vendedores\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "✅ Configuración completada\n";
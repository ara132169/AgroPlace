<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🛍️ VERIFICANDO COMPRAS DEL VENDEDOR DE PRUEBA\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Obtener el vendedor de prueba
    $seller = App\Models\Seller::where('email', 'vendedor@test.com')->first();
    
    if (!$seller) {
        echo "❌ Vendedor de prueba no encontrado\n";
        exit(1);
    }
    
    echo "👤 Vendedor: {$seller->name} (Email: {$seller->email})\n\n";
    
    // Buscar cliente con el mismo email
    $client = App\Models\Client::where('email', $seller->email)->first();
    
    if (!$client) {
        echo "ℹ️ No existe cliente con email {$seller->email}\n";
        echo "📝 Creando cliente para poder realizar compras...\n\n";
        
        // Crear cliente asociado
        $client = App\Models\Client::create([
            'name' => $seller->name,
            'email' => $seller->email,
            'password' => $seller->password, // Misma contraseña
            'verified' => 1,
            'email_verified_at' => now()
        ]);
        
        echo "✅ Cliente creado: ID {$client->id}\n\n";
        
        // Crear una orden de prueba para este cliente
        $order = App\Models\Order::create([
            'client_id' => $client->id,
            'shipping_name' => $client->name,
            'shipping_address' => 'Dirección de prueba 123',
            'shipping_company' => 'Empresa Test',
            'shipping_country' => 'México',
            'shipping_city' => 'Ciudad Test',
            'shipping_state' => 'Estado Test',
            'shipping_cp' => '12345',
            'shipping_phone' => '1234567890',
            'shipping_email' => $client->email,
            'total' => 500.00,
            'status' => 'pagado',
            'platform_fee' => 75.00, // 15%
            'seller_amount' => 425.00 // 85%
        ]);
        
        echo "🛒 Orden de compra creada: ID {$order->id}, Total: \${$order->total}\n\n";
        
        // Crear OrderItem asociado
        $product = App\Models\Product::first();
        if ($product) {
            App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 250.00
            ]);
            
            echo "📦 OrderItem creado para producto: {$product->name}\n";
        }
    } else {
        echo "✅ Cliente encontrado: ID {$client->id}, Nombre: {$client->name}\n\n";
    }
    
    // Verificar compras del cliente
    $compras = App\Models\Order::where('client_id', $client->id)->get();
    
    echo "🛍️ Compras del vendedor (como cliente):\n";
    
    if ($compras->count() > 0) {
        foreach ($compras as $compra) {
            echo "  - Orden #{$compra->id}: \${$compra->total} ({$compra->status})\n";
        }
    } else {
        echo "  ℹ️ No hay compras registradas\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n✅ Verificación completada\n";
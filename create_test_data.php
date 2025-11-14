<?php

/*
 * Script para crear datos de prueba para el sistema de comisiones
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔧 CREANDO DATOS DE PRUEBA PARA COMISIONES\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Actualizar órdenes existentes con información de comisiones
    $orders = App\Models\Order::whereNull('platform_fee')->take(5)->get();
    
    echo "📋 Órdenes a actualizar: " . $orders->count() . "\n\n";
    
    foreach ($orders as $order) {
        $platformFee = $order->total * 0.15;
        $sellerAmount = $order->total * 0.85;
        
        $order->update([
            'platform_fee' => $platformFee,
            'seller_amount' => $sellerAmount
        ]);
        
        echo "✅ Orden #{$order->id} actualizada:\n";
        echo "   Total: \${$order->total}\n";
        echo "   Comisión (15%): \${$platformFee}\n";
        echo "   Al vendedor (85%): \${$sellerAmount}\n\n";
    }
    
    // Ahora vamos a crear algunos OrderItems para asociar productos con vendedores
    $products = App\Models\Product::take(3)->get();
    $sellers = App\Models\Seller::take(2)->get();
    
    echo "🛍️ Productos disponibles: " . $products->count() . "\n";
    echo "👤 Vendedores disponibles: " . $sellers->count() . "\n\n";
    
    if ($products->count() > 0 && $sellers->count() > 0) {
        // Asignar productos a vendedores si no están asignados
        foreach ($products as $index => $product) {
            $seller = $sellers[$index % $sellers->count()];
            
            if (!$product->seller_id) {
                $product->update(['seller_id' => $seller->id]);
                echo "🔗 Producto '{$product->name}' asignado a '{$seller->name}'\n";
            }
        }
        
        // Crear OrderItems si no existen
        $orderWithoutItems = App\Models\Order::doesntHave('items')->first();
        
        if ($orderWithoutItems && $products->count() > 0) {
            $product = $products->first();
            
            App\Models\OrderItem::create([
                'order_id' => $orderWithoutItems->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => $product->price ?? 100.00
            ]);
            
            echo "🎯 OrderItem creado para orden #{$orderWithoutItems->id}\n";
        }
    }
    
    echo "\n📊 Resumen final:\n";
    echo "   Órdenes con comisión: " . App\Models\Order::whereNotNull('platform_fee')->count() . "\n";
    echo "   Productos con vendedor: " . App\Models\Product::whereNotNull('seller_id')->count() . "\n";
    echo "   Order items totales: " . App\Models\OrderItem::count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n✅ Datos de prueba creados exitosamente\n";
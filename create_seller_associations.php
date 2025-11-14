<?php

/*
 * Script para asociar OrderItems con vendedores específicos
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔗 CREANDO ASOCIACIONES VENDEDOR-ORDERITEMS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Obtener el primer vendedor
    $seller = App\Models\Seller::first();
    echo "👤 Vendedor: {$seller->name} (ID: {$seller->id})\n\n";
    
    // Obtener productos y asegurar que algunos pertenezcan a este vendedor
    $products = App\Models\Product::take(3)->get();
    
    foreach ($products as $index => $product) {
        if ($index < 2) { // Asignar los primeros 2 productos al vendedor
            $product->update(['seller_id' => $seller->id]);
            echo "✅ Producto '{$product->name}' (ID: {$product->id}) asignado a {$seller->name}\n";
        }
    }
    
    // Obtener órdenes con comisiones
    $ordersWithFee = App\Models\Order::whereNotNull('platform_fee')->take(3)->get();
    
    echo "\n📦 Creando OrderItems para asociar ventas al vendedor...\n\n";
    
    foreach ($ordersWithFee as $order) {
        // Verificar si ya tiene items
        $existingItems = App\Models\OrderItem::where('order_id', $order->id)->count();
        
        if ($existingItems == 0) {
            // Crear OrderItem con producto del vendedor
            $product = App\Models\Product::where('seller_id', $seller->id)->first();
            
            if ($product) {
                App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'price' => $product->price ?? 100.00
                ]);
                
                echo "🎯 OrderItem creado: Orden #{$order->id} + Producto '{$product->name}'\n";
            }
        } else {
            // Actualizar items existentes para que sean del vendedor
            $items = App\Models\OrderItem::where('order_id', $order->id)->get();
            foreach ($items as $item) {
                $product = App\Models\Product::where('seller_id', $seller->id)->first();
                if ($product) {
                    $item->update(['product_id' => $product->id]);
                    echo "🔄 OrderItem actualizado: Orden #{$order->id} ahora tiene producto del vendedor\n";
                    break; // Solo actualizar uno por orden
                }
            }
        }
    }
    
    // Verificar resultados
    echo "\n📊 Verificando asociaciones...\n";
    
    $ventasDelVendedor = App\Models\OrderItem::with(['order', 'product'])
        ->whereHas('product', function($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })
        ->get();
    
    echo "🛍️ Ventas encontradas para {$seller->name}: " . $ventasDelVendedor->count() . "\n";
    
    foreach ($ventasDelVendedor as $venta) {
        echo "   - Orden #{$venta->order->id}: \${$venta->order->total} (Comisión: \${$venta->order->platform_fee})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n✅ Asociaciones creadas exitosamente\n";
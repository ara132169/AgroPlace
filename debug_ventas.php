<?php

/*
 * Script para verificar la estructura de datos en ventas del vendedor
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔍 VERIFICANDO DATOS DE VENTAS DEL VENDEDOR\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Obtener el primer vendedor
    $seller = App\Models\Seller::first();
    
    if (!$seller) {
        echo "❌ No hay vendedores en la base de datos\n";
        exit(1);
    }
    
    echo "👤 Probando con vendedor: {$seller->name} (ID: {$seller->id})\n\n";
    
    // Simular la lógica del controlador misVentas()
    $ventas = App\Models\OrderItem::with(['order.client', 'product'])
        ->whereHas('product', function($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })
        ->with(['order' => function($query) {
            $query->withCount('items');
        }])
        ->latest()
        ->get()
        ->groupBy('order_id')
        ->map(function($items, $orderId) {
            $firstItem = $items->first();
            $order = $firstItem->order;
            $totalItems = $items->count();
            $totalAmount = $items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            return (object)[
                'id' => $orderId,
                'order' => $order,
                'items_count' => $totalItems,
                'total_seller_amount' => $totalAmount,
                'total' => $order->total,
                'platform_fee' => $order->platform_fee,
                'seller_amount' => $order->seller_amount,
                'created_at' => $order->created_at,
                'status' => $order->status,
                'client_name' => $order->client->name ?? 'Cliente no disponible',
                'items' => $items
            ];
        })
        ->values();
    
    echo "📊 Ventas encontradas: " . $ventas->count() . "\n\n";
    
    if ($ventas->count() > 0) {
        foreach ($ventas->take(3) as $index => $venta) {
            echo "🛍️ Venta #" . ($index + 1) . ":\n";
            echo "   ID: {$venta->id}\n";
            echo "   Total orden: \${$venta->total}\n";
            echo "   Platform fee: " . ($venta->platform_fee ?? 'null') . "\n";
            echo "   Seller amount: " . ($venta->seller_amount ?? 'null') . "\n";
            echo "   Status: {$venta->status}\n";
            echo "   Cliente: {$venta->client_name}\n";
            echo "   Items: {$venta->items_count}\n";
            echo "   ----\n";
        }
    } else {
        echo "ℹ️ Este vendedor no tiene ventas aún\n";
        
        // Probar con órdenes generales
        $totalOrders = App\Models\Order::count();
        echo "📋 Total de órdenes en sistema: {$totalOrders}\n";
        
        if ($totalOrders > 0) {
            $sampleOrder = App\Models\Order::with('items.product')->first();
            echo "🔍 Ejemplo de orden:\n";
            echo "   ID: {$sampleOrder->id}\n";
            echo "   Total: \${$sampleOrder->total}\n";
            echo "   Platform fee: " . ($sampleOrder->platform_fee ?? 'null') . "\n";
            echo "   Seller amount: " . ($sampleOrder->seller_amount ?? 'null') . "\n";
            echo "   Items: " . $sampleOrder->items->count() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n✅ Verificación completada\n";
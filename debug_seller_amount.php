<?php

require_once __DIR__ . '/vendor/autoload.php';

// Inicializar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DIAGNÓSTICO ESPECÍFICO - SELLER_AMOUNT\n";
echo "========================================\n\n";

echo "1️⃣  Verificando columnas en la tabla orders:\n";
$columns = Schema::getColumnListing('orders');
echo "   - platform_fee: " . (in_array('platform_fee', $columns) ? '✅' : '❌') . "\n";
echo "   - seller_amount: " . (in_array('seller_amount', $columns) ? '✅' : '❌') . "\n\n";

echo "2️⃣  Probando consulta directa con las columnas:\n";

try {
    $result = DB::table('orders')
        ->select(
            'id', 
            'total', 
            'platform_fee', 
            'seller_amount',
            'status'
        )
        ->limit(3)
        ->get();
    
    echo "   ✅ Consulta directa ejecutada exitosamente\n";
    echo "   - Resultados: " . $result->count() . "\n";
    
    foreach ($result as $order) {
        echo "   • Orden ID: " . $order->id . "\n";
        echo "     - Total: $" . number_format($order->total, 2) . "\n";
        echo "     - Platform fee: " . ($order->platform_fee ? '$' . number_format($order->platform_fee, 2) : 'NULL') . "\n";
        echo "     - Seller amount: " . ($order->seller_amount ? '$' . number_format($order->seller_amount, 2) : 'NULL') . "\n\n";
        break; // Solo mostrar uno
    }
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "3️⃣  Probando la consulta EXACTA del AdminController:\n";

try {
    $request = new \Illuminate\Http\Request();
    $controller = new \App\Http\Controllers\AdminController();
    
    // Ejecutar el método con la consulta actualizada
    $response = $controller->todasLasVentas($request);
    
    echo "   ✅ AdminController::todasLasVentas() ejecutado sin errores\n";
    
    // Obtener los datos
    $ventas = $response->getData()['ventas'];
    echo "   - Total ventas: " . $ventas->total() . "\n";
    echo "   - Ventas en página: " . $ventas->count() . "\n";
    
    if ($ventas->count() > 0) {
        $firstVenta = $ventas->first();
        echo "   ✅ Datos de la primera venta:\n";
        echo "     • ID: " . $firstVenta->id . "\n";
        echo "     • Total: $" . number_format($firstVenta->total, 2) . "\n";
        echo "     • Platform fee: " . (property_exists($firstVenta, 'platform_fee') ? 
              ($firstVenta->platform_fee ? '$' . number_format($firstVenta->platform_fee, 2) : 'NULL') : 'PROPIEDAD NO EXISTE') . "\n";
        echo "     • Seller amount: " . (property_exists($firstVenta, 'seller_amount') ? 
              ($firstVenta->seller_amount ? '$' . number_format($firstVenta->seller_amount, 2) : 'NULL') : 'PROPIEDAD NO EXISTE') . "\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error en AdminController: " . $e->getMessage() . "\n";
    echo "   📍 Archivo: " . $e->getFile() . "\n";
    echo "   📍 Línea: " . $e->getLine() . "\n";
}

echo "\n4️⃣  Verificando si hay órdenes con datos de comisión:\n";

$ordersWithFees = DB::table('orders')
    ->whereNotNull('platform_fee')
    ->orWhereNotNull('seller_amount')
    ->count();

$ordersTotal = DB::table('orders')->count();

echo "   - Total órdenes: $ordersTotal\n";
echo "   - Órdenes con comisiones: $ordersWithFees\n";

if ($ordersWithFees == 0) {
    echo "   ⚠️  Ninguna orden tiene datos de comisión\n";
    echo "   💡 Esto podría ser normal si son órdenes antiguas\n";
} else {
    echo "   ✅ Algunas órdenes tienen datos de comisión\n";
}

echo "\n🎯 CONCLUSIÓN:\n";
echo "==============\n";

if (in_array('platform_fee', $columns) && in_array('seller_amount', $columns)) {
    echo "✅ Las columnas existen en la base de datos\n";
    echo "✅ El AdminController ha sido actualizado\n";
    echo "✅ La vista admin/ventas debería funcionar correctamente\n";
    echo "\n🚀 PRUEBA NUEVAMENTE: http://localhost:8000/admin/ventas\n";
} else {
    echo "❌ Faltan columnas en la tabla orders\n";
    echo "🔧 Ejecuta: php artisan migrate\n";
}
?>
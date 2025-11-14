<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 VERIFICANDO ESTRUCTURA DE TABLA ORDERS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    $columns = DB::select('SHOW COLUMNS FROM orders');
    
    echo "📋 Columnas en tabla 'orders':\n";
    foreach ($columns as $col) {
        echo "  - {$col->Field} ({$col->Type})\n";
    }
    
    echo "\n🔍 VERIFICANDO ESTRUCTURA DE TABLA ORDER_ITEMS:\n";
    
    $orderItemColumns = DB::select('SHOW COLUMNS FROM order_items');
    echo "📋 Columnas en tabla 'order_items':\n";
    foreach ($orderItemColumns as $col) {
        echo "  - {$col->Field} ({$col->Type})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
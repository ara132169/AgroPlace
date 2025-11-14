<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 ANALIZANDO RELACIÓN VENDEDORES-COMPRAS\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Verificar vendedores
    $sellers = App\Models\Seller::take(5)->get();
    echo "👥 Vendedores en sistema:\n";
    foreach ($sellers as $seller) {
        echo "  - ID: {$seller->id}, Email: {$seller->email}, Nombre: {$seller->name}\n";
    }
    
    // Verificar clientes
    echo "\n👤 Clientes en sistema:\n";
    $clients = App\Models\Client::take(5)->get();
    foreach ($clients as $client) {
        echo "  - ID: {$client->id}, Email: {$client->email}, Nombre: {$client->name}\n";
    }
    
    // Verificar órdenes
    echo "\n📦 Órdenes en sistema:\n";
    $orders = App\Models\Order::take(5)->get();
    foreach ($orders as $order) {
        echo "  - ID: {$order->id}, Client ID: {$order->client_id}, Total: \${$order->total}\n";
    }
    
    // Verificar si hay emails duplicados entre sellers y clients
    echo "\n🔍 Verificando emails duplicados seller-client:\n";
    $sellerEmails = App\Models\Seller::pluck('email')->toArray();
    $clientEmails = App\Models\Client::pluck('email')->toArray();
    $duplicados = array_intersect($sellerEmails, $clientEmails);
    
    if (count($duplicados) > 0) {
        echo "  📧 Emails duplicados encontrados:\n";
        foreach ($duplicados as $email) {
            $seller = App\Models\Seller::where('email', $email)->first();
            $client = App\Models\Client::where('email', $email)->first();
            echo "    - {$email}: Seller ID {$seller->id} = Client ID {$client->id}\n";
        }
    } else {
        echo "  ℹ️ No hay emails duplicados entre sellers y clients\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
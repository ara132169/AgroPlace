<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Seller;

echo "🔧 CONFIGURANDO TODOS LOS VENDEDORES CON STRIPE\n";
echo "===============================================\n\n";

// Obtener todos los vendedores que tienen productos
$sellers = Seller::whereHas('products')->get();

echo "📊 Vendedores con productos encontrados: " . $sellers->count() . "\n\n";

foreach($sellers as $seller) {
    echo "🔄 Configurando: {$seller->name} (ID: {$seller->id})\n";
    
    $seller->update([
        'stripe_account_id' => 'acct_demo_' . $seller->id,
        'stripe_account_status' => 'active',
        'stripe_charges_enabled' => true,
        'stripe_payouts_enabled' => true
    ]);
    
    echo "   ✅ Stripe ID: acct_demo_{$seller->id}\n";
    echo "   ✅ Estado: active\n";
    echo "   ✅ Pagos habilitados: Sí\n\n";
}

echo "🎉 TODOS LOS VENDEDORES CONFIGURADOS\n";
echo "===================================\n";
echo "✅ Total configurados: " . $sellers->count() . "\n";
echo "🎯 Ahora el checkout debería funcionar correctamente\n";
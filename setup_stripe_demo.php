<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Seller;

echo "🔄 Configurando cuenta Stripe simulada...\n";

try {
    $seller = Seller::where('email', 'vendedor@test.com')->first();
    
    if (!$seller) {
        echo "❌ Vendedor no encontrado\n";
        exit;
    }
    
    $seller->update([
        'stripe_account_id' => 'acct_demo_' . $seller->id,
        'stripe_account_status' => 'active',
        'stripe_charges_enabled' => true,
        'stripe_payouts_enabled' => true
    ]);
    
    echo "✅ Cuenta Stripe simulada configurada:\n";
    echo "   📧 Email: {$seller->email}\n";
    echo "   🆔 Stripe ID: {$seller->stripe_account_id}\n";
    echo "   📊 Estado: {$seller->stripe_account_status}\n";
    echo "\n🎯 Ahora puedes probar el dashboard de Stripe Connect\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
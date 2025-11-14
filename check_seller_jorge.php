<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$seller = App\Models\Seller::find(13);

echo "=== VENDEDOR JORGE ===" . PHP_EOL;
echo "Nombre: " . $seller->name . PHP_EOL;
echo "Stripe Account ID: " . ($seller->stripe_account_id ?? 'NULL') . PHP_EOL;
echo "Stripe Status: " . ($seller->stripe_account_status ?? 'NULL') . PHP_EOL;
echo "Charges Enabled: " . ($seller->stripe_charges_enabled ? 'SÍ' : 'NO') . PHP_EOL;
echo "Payouts Enabled: " . ($seller->stripe_payouts_enabled ? 'SÍ' : 'NO') . PHP_EOL;

// Vamos a crear una cuenta REAL para este vendedor
echo PHP_EOL . "🔄 Creando cuenta REAL de Stripe Connect..." . PHP_EOL;

$stripeService = new App\Services\StripeConnectService();

try {
    // Intentar crear cuenta real
    $accountId = $stripeService->createConnectedAccount($seller);
    
    if ($accountId) {
        echo "✅ Cuenta creada exitosamente: " . $accountId . PHP_EOL;
        
        // Actualizar vendedor
        $seller->update([
            'stripe_account_id' => $accountId,
            'stripe_account_status' => 'active',
            'stripe_charges_enabled' => true,
            'stripe_payouts_enabled' => true,
        ]);
        
        echo "✅ Vendedor actualizado en BD" . PHP_EOL;
        
    } else {
        echo "❌ Error creando cuenta" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Excepción: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== ESTADO FINAL ===" . PHP_EOL;
$seller->refresh();
echo "Stripe Account ID: " . ($seller->stripe_account_id ?? 'NULL') . PHP_EOL;
echo "Stripe Status: " . ($seller->stripe_account_status ?? 'NULL') . PHP_EOL;
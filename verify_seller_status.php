<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Seller;

$seller = Seller::where('email', 'vendedor@test.com')->first();

echo "📊 Estado actual del vendedor:\n";
echo "Stripe ID: " . ($seller->stripe_account_id ?: 'No configurado') . "\n";
echo "Estado: " . ($seller->stripe_account_status ?: 'No configurado') . "\n";
echo "\n";

if ($seller->stripe_account_id) {
    echo "✅ El dashboard debería mostrar cuenta configurada\n";
    echo "⚠️  Pero los botones de onboarding seguirán fallando sin credenciales reales\n";
} else {
    echo "❌ La simulación se perdió, ejecuta setup_stripe_demo.php de nuevo\n";
}
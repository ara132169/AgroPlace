<?php

require_once 'bootstrap/app.php';
$kernel = app('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\ManualDeposit;
use App\Models\Seller;
use App\Models\SellerPaymentAccount;
use Illuminate\Support\Facades\Crypt;

echo "=== DATOS DE PRUEBA PARA DEPÓSITOS ===\n\n";

try {
    // Buscar vendedor existente
    $vendedor = Seller::first();
    if (!$vendedor) {
        echo "❌ No hay vendedores en la base de datos\n";
        exit(1);
    }
    echo "✅ Vendedor encontrado: {$vendedor->name}\n";

    // Buscar o crear cuenta de pago
    $cuenta = SellerPaymentAccount::where('seller_id', $vendedor->id)->first();
    if (!$cuenta) {
        $cuenta = SellerPaymentAccount::create([
            'seller_id' => $vendedor->id,
            'account_type' => 'credit_card',
            'account_holder_name' => $vendedor->name,
            'masked_card_number' => '****1234',
            'encrypted_card_number' => Crypt::encrypt('4111111111111234'),
            'bank_name' => 'BBVA',
            'is_verified' => true,
            'is_active' => true
        ]);
        echo "✅ Cuenta creada: {$cuenta->display_info}\n";
    } else {
        echo "✅ Cuenta encontrada: {$cuenta->display_info}\n";
    }

    // Crear depósito si no existe
    $deposito = ManualDeposit::where('seller_id', $vendedor->id)->where('status', 'pending')->first();
    if (!$deposito) {
        $deposito = ManualDeposit::create([
            'seller_id' => $vendedor->id,
            'payment_account_id' => $cuenta->id,
            'amount' => 72500.00,
            'currency' => 'MXN',
            'status' => 'pending',
            'description' => 'Depósito de prueba',
            'deposit_method' => 'manual'
        ]);
        echo "✅ Depósito creado: {$deposito->reference}\n";
    } else {
        echo "✅ Depósito encontrado: {$deposito->reference}\n";
    }

    echo "\n🎯 DATOS LISTOS:\n";
    echo "Depósito ID: {$deposito->id}\n";
    echo "URL de prueba: http://localhost:8001/admin/depositos/{$deposito->id}/detalles\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== FIN ===\n";
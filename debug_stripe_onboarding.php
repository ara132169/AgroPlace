<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Seller;
use App\Services\StripeConnectService;
use Illuminate\Support\Facades\Log;

echo "🔍 DEBUG: STRIPE CONNECT ONBOARDING\n";
echo "==================================\n\n";

try {
    // 1. Verificar configuración de Stripe
    echo "1️⃣  Verificando configuración Stripe...\n";
    $stripeKey = env('STRIPE_KEY');
    $stripeSecret = env('STRIPE_SECRET');
    
    echo "   STRIPE_KEY: " . ($stripeKey ? "✅ Configurado" : "❌ No configurado") . "\n";
    echo "   STRIPE_SECRET: " . ($stripeSecret ? "✅ Configurado" : "❌ No configurado") . "\n\n";

    // 2. Buscar vendedor de prueba
    echo "2️⃣  Buscando vendedor de prueba...\n";
    $seller = Seller::where('email', 'vendedor@test.com')->first();
    
    if (!$seller) {
        echo "   ❌ Vendedor no encontrado\n";
        exit;
    }
    
    echo "   ✅ Vendedor encontrado: ID {$seller->id}\n";
    echo "   📧 Email: {$seller->email}\n";
    echo "   🏪 Tienda: {$seller->name}\n\n";

    // 3. Verificar estado actual de Stripe
    echo "3️⃣  Estado actual Stripe Connect...\n";
    echo "   stripe_account_id: " . ($seller->stripe_account_id ?: "❌ No configurado") . "\n";
    echo "   stripe_account_status: " . ($seller->stripe_account_status ?: "❌ No configurado") . "\n\n";

    // 4. Probar creación de cuenta
    echo "4️⃣  Probando creación de cuenta Stripe...\n";
    
    if (!$stripeSecret) {
        echo "   ⚠️  No se puede probar sin STRIPE_SECRET configurado\n";
        echo "   💡 Para ambiente de desarrollo, usa claves de prueba de Stripe\n\n";
    } else {
        $stripeService = new StripeConnectService();
        
        try {
            if (!$seller->hasStripeAccount()) {
                echo "   🔄 Creando cuenta Stripe Connect...\n";
                $stripeService->createConnectedAccount($seller);
                $seller->refresh();
                echo "   ✅ Cuenta creada: {$seller->stripe_account_id}\n";
            } else {
                echo "   ℹ️  Cuenta ya existe: {$seller->stripe_account_id}\n";
            }
            
            // Verificar estado
            $accountStatus = $stripeService->checkAccountStatus($seller);
            echo "   📊 Estado cuenta: " . ($accountStatus ? "✅ Activa" : "⏳ Pendiente") . "\n\n";
            
        } catch (\Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n\n";
        }
    }

    // 5. URLs de callback
    echo "5️⃣  URLs de callback configuradas...\n";
    echo "   Success URL: http://127.0.0.1:8000/tienda/stripe/success\n";
    echo "   Refresh URL: http://127.0.0.1:8000/tienda/stripe/refresh\n\n";

    // 6. Recomendaciones
    echo "🎯 RECOMENDACIONES:\n";
    echo "==================\n";
    
    if (!$stripeKey || !$stripeSecret) {
        echo "❌ CRÍTICO: Configurar credenciales Stripe en .env:\n";
        echo "   STRIPE_KEY=pk_test_xxxxxxxxxxxx\n";
        echo "   STRIPE_SECRET=sk_test_xxxxxxxxxxxx\n\n";
        echo "📍 Obtener en: https://dashboard.stripe.com/test/apikeys\n\n";
    }
    
    echo "✅ Para probar en modo desarrollo:\n";
    echo "   1. Registrarse en Stripe (gratis)\n";
    echo "   2. Obtener claves de TEST (no producción)\n";
    echo "   3. Configurar en archivo .env\n";
    echo "   4. Probar onboarding con datos ficticios\n\n";

    echo "🔄 ESTADO FINAL DEL VENDEDOR:\n";
    echo "============================\n";
    $seller->refresh();
    echo "ID: {$seller->id}\n";
    echo "Email: {$seller->email}\n";
    echo "Stripe Account ID: " . ($seller->stripe_account_id ?: "No configurado") . "\n";
    echo "Stripe Status: " . ($seller->stripe_account_status ?: "No configurado") . "\n";

} catch (\Exception $e) {
    echo "❌ ERROR GENERAL: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✨ Debug completado\n";
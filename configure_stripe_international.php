<?php

echo "
===========================================
🇺🇸 CONFIGURACIÓN STRIPE CONNECT INTERNACIONAL
===========================================

Para activar la división automática de pagos, necesitas:

📋 PASO 1: CONFIGURAR PLATAFORMA INTERNACIONAL
---------------------------------------------
1. Crear cuenta Stripe en US (https://stripe.com)
2. Activar Stripe Connect
3. Configurar webhook endpoint:
   https://tudominio.com/webhook/stripe

🏪 PASO 2: VENDEDORES CONFIGURAN CUENTAS
----------------------------------------
1. Vendedor va a: Panel > Configurar Pagos
2. Click 'Conectar con Stripe'
3. Completa proceso Stripe Express
4. Verifica identidad y cuenta bancaria

💻 PASO 3: MODIFICAR CÓDIGO
---------------------------
En CheckoutController.php línea ~790:

Cambiar:
    return false; // Cambiar a true cuando se resuelvan restricciones

Por:
    return true; // ✅ ACTIVADO

🔄 FLUJO AUTOMÁTICO RESULTANTE:
------------------------------
1. Cliente compra $20,000 MXN
2. Sistema detecta vendedor con Stripe Connect
3. Cargo automático:
   - $17,000 → Cuenta del vendedor (directo)
   - $3,000 → Plataforma (comisión 15%)
4. Sin intervención manual

⚠️  RESTRICCIONES ACTUALES:
---------------------------
❌ México → México: NO soportado por Stripe
✅ US/EU → México: SÍ soportado
✅ México → US/EU: SÍ soportado

💡 SOLUCIÓN TEMPORAL ACTUAL:
----------------------------
- Pago directo + cálculo manual de comisiones
- Todas las funciones implementadas
- Base lista para activación internacional

🎯 BENEFICIO AL ACTIVAR CONNECT:
-------------------------------
- Pagos inmediatos al vendedor (85%)
- Sin procesos manuales
- Mayor confianza del vendedor
- Flujo automatizado completo

";

echo "
📞 PARA ACTIVAR HOY:
-------------------
1. Configura Stripe US/EU
2. Cambia el 'return false' por 'return true'
3. Los vendedores podrán conectar sus cuentas
4. División automática activada

¿Quieres que active el modo Connect ahora? (S/N): ";

$input = strtoupper(trim(fgets(STDIN)));

if ($input === 'S' || $input === 'SI' || $input === 'Y' || $input === 'YES') {
    echo "\n🔄 Activando Stripe Connect...\n";
    
    // Modificar el archivo automáticamente
    $file = 'app/Http/Controllers/Client/CheckoutController.php';
    $content = file_get_contents($file);
    
    if (strpos($content, 'return false; // Cambiar a true cuando se resuelvan restricciones') !== false) {
        $content = str_replace(
            'return false; // Cambiar a true cuando se resuelvan restricciones',
            'return true; // ✅ CONNECT ACTIVADO - División automática habilitada',
            $content
        );
        
        file_put_contents($file, $content);
        echo "✅ Stripe Connect ACTIVADO en CheckoutController.php\n";
        echo "🎯 Los vendedores ahora pueden configurar sus cuentas\n";
        echo "💰 División automática habilitada\n\n";
        echo "📋 PRÓXIMOS PASOS:\n";
        echo "1. Vendedores: Panel > Configurar Pagos > Conectar Stripe\n";
        echo "2. Sistema automáticamente detectará y dividirá pagos\n";
        echo "3. 85% directo al vendedor, 15% a plataforma\n";
        
    } else {
        echo "⚠️  No se encontró el código a modificar\n";
        echo "Modifica manualmente en CheckoutController.php línea ~800\n";
    }
    
} else {
    echo "\n✅ Mantienes configuración actual (modo directo)\n";
    echo "💡 Puedes activar Connect cuando configures cuentas internacionales\n";
}

echo "\n===========================================\n";
<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔐 RESET DE CONTRASEÑAS PARA PRUEBAS\n";
echo "====================================\n\n";

// Resetear contraseña del vendedor
$seller = App\Models\Seller::find(13);
$newSellerPassword = 'test123';
$seller->update(['password' => bcrypt($newSellerPassword)]);

echo "✅ Contraseña del vendedor actualizada:\n";
echo "Email: melendezv@gmail.com\n";
echo "Password: {$newSellerPassword}\n\n";

// Resetear contraseña del admin
$admin = App\Models\Admin::first();
$newAdminPassword = 'admin123';
$admin->update(['password' => bcrypt($newAdminPassword)]);

echo "✅ Contraseña del admin actualizada:\n";
echo "Email: admin@email.com\n";
echo "Password: {$newAdminPassword}\n\n";

echo "🎯 ACCESOS DIRECTOS:\n";
echo "===================\n";
echo "🏪 Vendedor: http://localhost:8000/tienda/ingresar\n";
echo "   → Email: melendezv@gmail.com\n";
echo "   → Password: test123\n\n";

echo "👨‍💼 Admin: http://localhost:8000/admin/login\n";
echo "   → Email: admin@email.com\n";
echo "   → Password: admin123\n\n";

echo "📱 URLs PARA PROBAR:\n";
echo "===================\n";
echo "💳 Cuentas de pago: http://localhost:8000/tienda/payment-accounts\n";
echo "📊 Historial depósitos: http://localhost:8000/tienda/deposit-history\n";
echo "🎯 Panel admin depósitos: http://localhost:8000/admin/deposits\n\n";

echo "✅ ¡SISTEMA LISTO PARA PROBAR!\n";
?>
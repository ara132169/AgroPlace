<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Client;

$email = 'abraham3270@gmail.com';
echo "🔍 Verificando cliente: {$email}\n";

$client = Client::firstOrCreate([
    'email' => $email
], [
    'name' => 'Abraham Rodriguez', 
    'password' => bcrypt('password123'),
    'phone' => '2221123948',
    'status' => 1,
    'email_verified_at' => now()
]);

echo "✅ Cliente listo: ID {$client->id} - {$client->email}\n";
echo "🎯 Ahora puedes usar el checkout de prueba\n";
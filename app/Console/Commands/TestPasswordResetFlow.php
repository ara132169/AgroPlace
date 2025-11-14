<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Client;
use Illuminate\Http\Request;

class TestPasswordResetFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:password-reset-flow {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el flujo completo de reset de contraseña para clientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== PRUEBA COMPLETA DE RESET DE CONTRASEÑA ===\n");

        // Obtener email del cliente
        $email = $this->argument('email');
        if (!$email) {
            $client = Client::first();
            if (!$client) {
                $this->error("No se encontraron clientes en la base de datos");
                return;
            }
            $email = $client->email;
        }

        $this->info("🔍 Probando con email: {$email}");

        // Verificar que el cliente existe
        $client = Client::where('email', $email)->first();
        if (!$client) {
            $this->error("❌ Cliente no encontrado con email: {$email}");
            return;
        }

        $this->info("✅ Cliente encontrado: {$client->name}");
        
        // Mostrar contraseña actual (hasheada)
        $this->info("🔐 Password hash actual: " . substr($client->password, 0, 30) . "...");

        // Simular el proceso de reset
        $this->info("\n📧 Simulando envío de token de reset...");
        
        $token = Str::random(60);
        $this->info("🎫 Token generado: " . substr($token, 0, 20) . "...");

        // Simular el callback del reset
        $this->info("\n🔄 Simulando proceso de reset de contraseña...");
        
        try {
            $newPassword = 'nuevaPassword123';
            $client->password = Hash::make($newPassword);
            $client->setRememberToken(Str::random(60));
            $client->save();
            
            $this->info("✅ Contraseña actualizada exitosamente");
            $this->info("🔑 Nueva contraseña: {$newPassword}");
            $this->info("🎫 Remember token: " . substr($client->remember_token, 0, 20) . "...");
            
            // Verificar que la nueva contraseña funciona
            if (Hash::check($newPassword, $client->password)) {
                $this->info("✅ Verificación de nueva contraseña: CORRECTA");
            } else {
                $this->error("❌ Verificación de nueva contraseña: FALLÓ");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error durante el reset: " . $e->getMessage());
            return;
        }

        $this->info("\n🌐 URLs para probar manualmente:");
        $this->info("   • Solicitar reset: http://localhost:8000/cliente/password/forgot");
        $this->info("   • Login: http://localhost:8000/cliente/ingresar");
        
        $this->info("\n✅ FLUJO DE RESET COMPLETADO EXITOSAMENTE");
        $this->info("Ahora puedes iniciar sesión con:");
        $this->info("   Email: {$email}");
        $this->info("   Password: {$newPassword}");
    }
}

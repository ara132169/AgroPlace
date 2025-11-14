<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientRegistrationConfirmation;
use App\Models\Client;
use App\Models\GeneralSetting;

class TestClientEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:client-email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el envío de correo de confirmación de registro de cliente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'prueba@example.com';
        
        // Mostrar configuración actual del sitio
        $siteSettings = GeneralSetting::first();
        if ($siteSettings) {
            $this->info("🏢 Configuración del sitio detectada:");
            $this->info("   • Nombre: " . ($siteSettings->site_name ?: 'No configurado'));
            $this->info("   • Email: " . ($siteSettings->site_email ?: 'No configurado'));
            $this->info("   • Teléfono: " . ($siteSettings->site_phone ?: 'No configurado'));
            $this->info("   • Logo: " . ($siteSettings->site_logo ? '✅ Configurado' : '❌ No configurado'));
            $this->info("");
        } else {
            $this->warn("⚠️  No se encontraron configuraciones del sitio. Se usarán valores por defecto.");
            $this->info("");
        }
        
        // Crear un cliente de prueba
        $clientData = [
            'id' => 999,
            'name' => 'Cliente de Prueba',
            'username' => 'cliente_prueba',
            'email' => $email,
            'created_at' => now(),
            'picture' => null
        ];
        
        // Convertir a objeto para simular un modelo
        $client = (object) $clientData;
        
        try {
            Mail::to($client->email)->send(new ClientRegistrationConfirmation($client));
            $this->info("✅ Correo de confirmación enviado exitosamente a: " . $client->email);
            $this->info("📧 Revisa tu bandeja de entrada (y spam) para ver el correo.");
        } catch (\Exception $e) {
            $this->error("❌ Error enviando el correo: " . $e->getMessage());
            $this->error("📋 Verifica la configuración SMTP en el archivo .env");
        }
    }
}

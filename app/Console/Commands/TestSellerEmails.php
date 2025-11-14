<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Seller;
use App\Mail\SellerRegistrationConfirmation;
use App\Mail\SellerAccountApproved;
use App\Mail\NewSellerRegistrationNotification;

class TestSellerEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:seller-emails {email} {--type=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba los emails del sistema de vendedores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $type = $this->option('type');
        
        // Crear un vendedor de prueba
        $testSeller = new Seller([
            'name' => 'Tienda de Prueba AgroPlace',
            'email' => $email,
        ]);
        $testSeller->id = 999; // ID ficticio para pruebas
        $testSeller->created_at = now(); // Asignar fecha actual
        $testSeller->updated_at = now(); // Asignar fecha actual
        
        $this->info("🧪 Probando sistema de emails para vendedores...");
        $this->info("📧 Email de destino: {$email}");
        
        if ($type === 'all' || $type === 'registration') {
            $this->info("\n📤 Enviando email de confirmación de registro...");
            try {
                \Mail::to($email)->send(new SellerRegistrationConfirmation($testSeller));
                $this->info("✅ Email de confirmación de registro enviado exitosamente");
            } catch (\Exception $e) {
                $this->error("❌ Error enviando email de confirmación: " . $e->getMessage());
            }
        }
        
        if ($type === 'all' || $type === 'approved' || $type === 'approval') {
            $this->info("\n📤 Enviando email de cuenta aprobada...");
            try {
                \Mail::to($email)->send(new SellerAccountApproved($testSeller));
                $this->info("✅ Email de cuenta aprobada enviado exitosamente");
            } catch (\Exception $e) {
                $this->error("❌ Error enviando email de aprobación: " . $e->getMessage());
            }
        }
        
        if ($type === 'all' || $type === 'admin') {
            $this->info("\n📤 Enviando notificación al administrador...");
            try {
                $adminEmail = config('mail.admin_email', 'admin@agroplace.com');
                \Mail::to($adminEmail)->send(new NewSellerRegistrationNotification($testSeller));
                $this->info("✅ Notificación al administrador enviada exitosamente a: {$adminEmail}");
            } catch (\Exception $e) {
                $this->error("❌ Error enviando notificación al admin: " . $e->getMessage());
            }
        }
        
        $this->info("\n🎉 Prueba de emails completada!");
        $this->info("💡 Tipos disponibles: --type=registration, --type=approval, --type=admin, --type=all");
        
        return 0;
    }
}

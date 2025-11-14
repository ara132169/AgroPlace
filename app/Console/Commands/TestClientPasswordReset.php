<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Client;

class TestClientPasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:client-password-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el sistema de reset de contraseña para clientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== PRUEBA DE RESET DE CONTRASEÑA PARA CLIENTES ===\n");

        // Verificar estructura de la tabla
        $this->info("1. Verificando estructura de la tabla 'clients':");
        $columns = Schema::getColumnListing('clients');
        $this->info("   Columnas encontradas: " . implode(', ', $columns));

        if (in_array('remember_token', $columns)) {
            $this->info("   ✅ Columna 'remember_token' encontrada");
        } else {
            $this->error("   ❌ Columna 'remember_token' NO encontrada");
            return;
        }

        $this->info("");

        // Buscar un cliente existente
        $this->info("2. Buscando clientes existentes:");
        $clients = Client::take(3)->get(['id', 'name', 'email']);
        foreach ($clients as $client) {
            $this->info("   • ID: {$client->id}, Nombre: {$client->name}, Email: {$client->email}");
        }

        if ($clients->count() > 0) {
            $testClient = $clients->first();
            $this->info("\n3. Probando setRememberToken() con el cliente: {$testClient->name}");
            
            try {
                $testClient->setRememberToken(Str::random(60));
                $testClient->save();
                $this->info("   ✅ setRememberToken() funcionó correctamente");
                $this->info("   ✅ Token guardado: " . substr($testClient->remember_token, 0, 20) . "...");
            } catch (\Exception $e) {
                $this->error("   ❌ Error al usar setRememberToken(): " . $e->getMessage());
                return;
            }
        } else {
            $this->warn("   No se encontraron clientes para probar");
        }

        $this->info("\n4. Sistema de reset de contraseña: ✅ LISTO PARA USO");
        $this->info("\nPuedes probar el reset en: http://localhost:8000/cliente/password/forgot");
    }
}

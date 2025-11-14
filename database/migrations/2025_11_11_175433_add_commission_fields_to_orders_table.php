<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Solo agregar campos de comisiones (los de Stripe ya existen)
            $table->decimal('platform_fee', 10, 2)->nullable()->comment('Comisión de la plataforma (15%)');
            $table->decimal('seller_amount', 10, 2)->nullable()->comment('Monto que recibe el vendedor después de comisión');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'platform_fee',
                'seller_amount'
            ]);
        });
    }
};

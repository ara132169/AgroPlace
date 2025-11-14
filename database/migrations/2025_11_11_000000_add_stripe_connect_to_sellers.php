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
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable()->after('payment_email');
            $table->string('stripe_account_status')->default('pending')->after('stripe_account_id'); // pending, active, restricted
            $table->boolean('stripe_charges_enabled')->default(false)->after('stripe_account_status');
            $table->boolean('stripe_payouts_enabled')->default(false)->after('stripe_charges_enabled');
            $table->decimal('commission_rate', 5, 2)->default(15.00)->after('stripe_payouts_enabled'); // Porcentaje de comisión
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_account_id',
                'stripe_account_status', 
                'stripe_charges_enabled',
                'stripe_payouts_enabled',
                'commission_rate'
            ]);
        });
    }
};
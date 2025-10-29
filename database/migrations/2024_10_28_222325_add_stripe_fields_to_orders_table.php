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
            $table->string('payment_method')->default('stripe')->after('status');
            $table->string('stripe_payment_intent_id')->nullable()->after('payment_method');
            $table->string('stripe_payment_status')->default('pending')->after('stripe_payment_intent_id');
            $table->string('payment_currency', 3)->default('EUR')->after('stripe_payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'stripe_payment_intent_id', 'stripe_payment_status', 'payment_currency']);
        });
    }
};

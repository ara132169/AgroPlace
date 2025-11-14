<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manual_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('order_id')->nullable(); // Si es pago de orden específica
            $table->unsignedBigInteger('payment_account_id'); // Cuenta destino
            $table->unsignedBigInteger('processed_by')->nullable(); // Admin que procesó
            
            $table->decimal('amount', 10, 2); // Monto a depositar
            $table->string('currency', 3)->default('MXN');
            $table->string('reference')->unique(); // Referencia única
            $table->text('description')->nullable();
            
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Información del depósito
            $table->string('deposit_method')->nullable(); // stripe_transfer, bank_transfer, manual
            $table->string('external_transaction_id')->nullable(); // ID de Stripe/banco
            $table->text('admin_notes')->nullable();
            $table->text('failure_reason')->nullable();
            
            $table->timestamps();
            
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('payment_account_id')->references('id')->on('seller_payment_accounts')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('admins')->onDelete('set null');
            
            $table->index(['seller_id', 'status']);
            $table->index(['status', 'requested_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_deposits');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seller_payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('account_type')->default('debit_card'); // debit_card, bank_account, paypal
            $table->string('account_holder_name');
            
            // Para tarjeta de débito
            $table->string('card_last_four')->nullable();
            $table->string('card_brand')->nullable(); // visa, mastercard
            $table->string('card_token')->nullable(); // Token encriptado
            
            // Para cuenta bancaria
            $table->string('bank_name')->nullable();
            $table->string('account_number_last_four')->nullable();
            $table->string('clabe')->nullable(); // Para México
            
            // Para PayPal/otros
            $table->string('paypal_email')->nullable();
            $table->string('other_account_info')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable(); // admin ID
            
            $table->timestamps();
            
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_payment_accounts');
    }
};
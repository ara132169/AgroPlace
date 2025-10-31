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
            // Agregar campos para soportar vendedores como compradores
            $table->unsignedBigInteger('seller_id')->nullable()->after('client_id');
            $table->enum('buyer_type', ['client', 'seller'])->default('client')->after('seller_id');
            
            // Hacer client_id nullable ya que puede ser un vendedor
            $table->unsignedBigInteger('client_id')->nullable()->change();
            
            // Agregar foreign key para sellers
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn(['seller_id', 'buyer_type']);
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
        });
    }
};

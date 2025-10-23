<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('shipping_name');
            $table->string('shipping_address');
              $table->string('shipping_company');
            $table->string('shipping_country');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_cp');
            $table->string('shipping_phone');
            $table->string('shipping_email');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pendiente', 'pagado', 'enviado', 'completado'])->default('pendiente');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};

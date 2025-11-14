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
        Schema::table('seller_payment_accounts', function (Blueprint $table) {
            // Campos para almacenar información completa encriptada
            $table->text('encrypted_card_number')->nullable()->after('card_token');
            $table->text('encrypted_account_number')->nullable()->after('clabe');
            $table->text('encrypted_clabe')->nullable()->after('clabe');
            $table->text('admin_notes')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_payment_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'encrypted_card_number',
                'encrypted_account_number', 
                'encrypted_clabe',
                'admin_notes'
            ]);
        });
    }
};

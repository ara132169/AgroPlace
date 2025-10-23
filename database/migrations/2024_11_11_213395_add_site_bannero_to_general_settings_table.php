<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('site_bannero')->nullable();
            $table->string('site_bannert')->nullable();
            $table->string('site_bannerth')->nullable();
            $table->string('site_bannerf')->nullable();
            $table->string('site_bannerfiv')->nullable();
        });
    }

    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_bannero',
                'site_bannert',
                'site_bannerth',
                'site_bannerf',
                'site_bannerfiv',
            ]);
        });
    }
};

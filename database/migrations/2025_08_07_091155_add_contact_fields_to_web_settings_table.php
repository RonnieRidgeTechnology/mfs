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
        Schema::table('web_settings', function (Blueprint $table) {
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->longText('address')->nullable();
            $table->longText('address_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn(['email1', 'email2', 'phone1', 'phone2', 'address', 'address_link']);
        });
    }
};

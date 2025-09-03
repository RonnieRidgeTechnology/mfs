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
        Schema::table('users', function (Blueprint $table) {
            $table->string('street')->nullable()->after('phone');
            $table->string('area')->nullable()->after('street');
            $table->string('town')->nullable()->after('area');
            $table->string('postal_code')->nullable()->after('town');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['street', 'area', 'town', 'postal_code']);
        });
    }
};

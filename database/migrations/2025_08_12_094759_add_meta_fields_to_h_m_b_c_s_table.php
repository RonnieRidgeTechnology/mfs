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
        Schema::table('h_m_b_c_s', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->text('meta_desc')->nullable();
            $table->string('meta_keyword')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('h_m_b_c_s', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_desc', 'meta_keyword']);
        });
    }
};

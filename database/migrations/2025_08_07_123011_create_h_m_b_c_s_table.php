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
        Schema::create('h_m_b_c_s', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longtext('desc')->nullable();
            $table->string('location_title')->nullable();
            $table->longtext('location_desc')->nullable();
            $table->string('location_link')->nullable();
            $table->string('member_title')->nullable();
            $table->longtext('member_desc')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_m_b_c_s');
    }
};

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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // user who did the action
            $table->string('email')->nullable();               // store email
            $table->string('role')->nullable();                // store user role like admin/customer
            $table->string('action_type')->nullable(); // like 'login', 'logout'
            $table->text('activity');                       // e.g. "Login Success"
            $table->ipAddress('ip_address')->nullable();       // device IP
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

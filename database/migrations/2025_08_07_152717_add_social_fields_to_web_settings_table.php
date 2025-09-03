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
            $table->string('facebook_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->string('linkdin_link')->nullable();
            $table->string('copy_right')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_link',
                'youtube_link',
                'insta_link',
                'linkdin_link',
                'copy_right',
            ]);
        });
    }
};

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
        Schema::create('home_updates', function (Blueprint $table) {
            $table->id();
            $table->string('main_title')->nullable();
            $table->longText('main_desc')->nullable();
            $table->string('main_image1')->nullable();
            $table->string('main_image2')->nullable();

            $table->string('section1_main_title')->nullable();
            $table->string('section1_title')->nullable();
            $table->text('section1_desc')->nullable();
            $table->longText('section1_points')->nullable();
            $table->string('section1_image1')->nullable();
            $table->string('section1_image2')->nullable();
            $table->string('section1_image3')->nullable();

            $table->string('section2_title')->nullable();
            $table->longText('section2_desc')->nullable();

            $table->string('section3_main_title')->nullable();
            $table->string('section3_title')->nullable();
            $table->longText('section3_desc')->nullable();
            $table->string('section3_image')->nullable();

            $table->string('footer_main_title')->nullable();
            $table->longText('footer_main_desc')->nullable();
            $table->string('footer_title')->nullable();
            $table->longText('footer_desc')->nullable();

            $table->string('meta_title')->nullable();
            $table->longText('meta_desc')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_updates');
    }
};

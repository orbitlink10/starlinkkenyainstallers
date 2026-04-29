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
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_header_title');
            $table->text('hero_header_description')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('why_choose_title')->nullable();
            $table->text('why_choose_description')->nullable();
            $table->string('products_section_title')->nullable();
            $table->longText('home_page_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};

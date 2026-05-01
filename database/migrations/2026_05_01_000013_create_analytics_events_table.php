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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 40)->index();
            $table->string('visitor_id', 80)->index();
            $table->string('path', 255)->nullable();
            $table->string('label')->nullable();
            $table->string('page_type', 50)->nullable()->index();
            $table->string('referrer_host')->nullable()->index();
            $table->text('referrer_url')->nullable();
            $table->json('properties')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};

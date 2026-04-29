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
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->decimal('marked_price', 12, 2)->nullable()->after('price');
            $table->unsignedInteger('quantity')->nullable()->after('stock');
            $table->foreignId('category_id')->nullable()->after('quantity')->constrained()->nullOnDelete();
            $table->foreignId('sub_category_id')->nullable()->after('category_id')->constrained('sub_categories')->nullOnDelete();
            $table->text('meta_description')->nullable()->after('sub_category_id');
            $table->longText('description')->nullable()->after('meta_description');
            $table->boolean('google_merchant')->default(false)->after('description');
            $table->string('image_path')->nullable()->after('google_merchant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('sub_category_id');
            $table->dropColumn([
                'slug',
                'marked_price',
                'quantity',
                'meta_description',
                'description',
                'google_merchant',
                'image_path',
            ]);
        });
    }
};

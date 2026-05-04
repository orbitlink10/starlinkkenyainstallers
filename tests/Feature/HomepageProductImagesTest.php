<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageProductImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_uploaded_product_images_and_local_placeholder_fallback(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/standard-kit.jpg', 'image-bytes');

        Product::query()->create([
            'name' => 'Starlink Standard Kit',
            'slug' => 'starlink-standard-kit',
            'price' => 45000,
            'stock' => 10,
            'is_active' => true,
            'image_path' => 'products/standard-kit.jpg',
        ]);

        Product::query()->create([
            'name' => 'Starlink Mini Kit',
            'slug' => 'starlink-mini-kit',
            'price' => 30000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('media.show', ['path' => 'products/standard-kit.jpg']), false);
        $response->assertSee(asset('images/product-placeholder.svg'), false);
        $response->assertDontSee('images.unsplash.com', false);
    }
}

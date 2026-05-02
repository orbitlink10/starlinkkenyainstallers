<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SitePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_meta_tags_and_search_results_are_noindex(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<meta name="description" content="', false);
        $response->assertSee('<link rel="canonical" href="http://localhost">', false);
        $response->assertSee('<meta name="robots" content="index,follow">', false);
        $response->assertSee('"@type":"WebSite"', false);
        $response->assertSee('"@type":"ProfessionalService"', false);

        Product::query()->create([
            'name' => 'Starlink Search Kit',
            'slug' => 'starlink-search-kit',
            'price' => 49999,
            'stock' => 4,
            'quantity' => 4,
            'is_active' => true,
        ]);

        $searchResponse = $this->get('/?q=search');

        $searchResponse->assertOk();
        $searchResponse->assertSee('<meta name="robots" content="noindex,follow">', false);
        $searchResponse->assertSee('<link rel="canonical" href="http://localhost">', false);
    }

    public function test_product_pages_render_canonical_and_product_schema(): void
    {
        $product = Product::query()->create([
            'name' => 'Starlink Standard Router Kit',
            'slug' => 'starlink-standard-router-kit',
            'price' => 49999,
            'stock' => 12,
            'quantity' => 12,
            'meta_description' => 'Buy the Starlink Standard Router Kit in Kenya.',
            'description' => '<p>Reliable hardware for homes and offices.</p>',
            'is_active' => true,
        ]);

        $response = $this->get(route('shop.product.show', ['productSlug' => $product->slug]));

        $response->assertOk();
        $response->assertSee('<meta property="og:type" content="product">', false);
        $response->assertSee('content="Buy the Starlink Standard Router Kit in Kenya.', false);
        $response->assertSee('<link rel="canonical" href="http://localhost/product/starlink-standard-router-kit">', false);
        $response->assertSee('"@type":"Product"', false);
        $response->assertSee('"priceCurrency":"KES"', false);
    }

    public function test_site_pages_can_be_noindexed_and_sitemap_lists_public_urls(): void
    {
        $page = SitePage::query()->create([
            'page_title' => 'Starlink Roaming in Kenya',
            'slug' => 'starlink-roaming-in-kenya',
            'meta_description' => 'Archived guidance for legacy roaming plans.',
            'type' => 'Post',
            'page_description' => '<p>Read the full guide from <a href="https://starlinkkenya.co.ke/public/starlink-kenya-prices">Starlink Kenya</a>.</p>',
        ]);

        $pageResponse = $this->get(route('site-pages.show', ['page' => $page->slug]));

        $pageResponse->assertOk();
        $pageResponse->assertSee('<meta name="robots" content="noindex,follow">', false);
        $pageResponse->assertSee('"@type":"Article"', false);
        $pageResponse->assertDontSee('href="https://starlinkkenya.co.ke/public/starlink-kenya-prices"', false);
        $pageResponse->assertSeeText('Read the full guide from Starlink Kenya.');

        $product = Product::query()->create([
            'name' => 'Starlink Mini Kit',
            'slug' => 'starlink-mini-kit',
            'price' => 39999,
            'stock' => 5,
            'quantity' => 5,
            'is_active' => true,
        ]);

        $sitemapResponse = $this->get(route('seo.sitemap'));

        $sitemapResponse->assertOk();
        $sitemapResponse->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $sitemapResponse->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
        $sitemapResponse->assertSee(route('home'), false);
        $sitemapResponse->assertSee(route('site-pages.show', ['page' => $page->slug]), false);
        $sitemapResponse->assertSee(route('shop.product.show', ['productSlug' => $product->slug]), false);

        $robotsResponse = $this->get(route('seo.robots'));

        $robotsResponse->assertOk();
        $robotsResponse->assertSee('Sitemap: http://localhost/sitemap.xml');
        $robotsResponse->assertSee('Disallow: /admin');
        $robotsResponse->assertSee('Disallow: /cart');
    }
}

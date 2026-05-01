<?php

namespace Tests\Feature;

use App\Models\SitePage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitePageViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_page_uses_hero_layout_and_hides_duplicate_leading_heading(): void
    {
        $page = SitePage::query()->create([
            'page_title' => 'Starlink Nairobi Subscription Suspension',
            'slug' => 'starlink-nairobi-subscription-suspension',
            'meta_description' => 'Learn how to fix Starlink Nairobi Subscription Suspension fast.',
            'type' => 'Post',
            'page_description' => '<h1>Starlink Nairobi Subscription Suspension: The Critical Guide to Restoring Your Internet Fast in 2026</h1><p>Discover causes, reactivation steps, and expert tips.</p>',
        ]);

        $page->forceFill([
            'created_at' => Carbon::parse('2026-03-05 10:00:00'),
            'updated_at' => Carbon::parse('2026-03-05 10:00:00'),
        ])->save();

        $response = $this->get('/starlink-nairobi-subscription-suspension');

        $response->assertOk();
        $response->assertSeeText('STARLINK');
        $response->assertSeeText('KENYA INSTALLERS');
        $response->assertSee('placeholder="Search for products..."', false);
        $response->assertSeeText('Mar 05, 2026');
        $response->assertSeeText('Starlink Nairobi Subscription Suspension');
        $response->assertSeeText('Learn how to fix Starlink Nairobi Subscription Suspension fast.');
        $response->assertSeeText('Back');
        $response->assertSeeText('Shop Now');
        $response->assertSeeText('Talk to an Expert');
        $response->assertSeeText('Starlink Nairobi Subscription Suspension: The Critical Guide to Restoring Your Internet Fast in 2026');
        $response->assertSeeText('Discover causes, reactivation steps, and expert tips.');
    }
}

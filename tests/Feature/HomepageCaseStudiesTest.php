<?php

namespace Tests\Feature;

use App\Models\SitePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageCaseStudiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_case_study_cards_with_read_more_links(): void
    {
        SitePage::query()->create([
            'page_title' => 'University of Nairobi Connectivity Upgrade',
            'slug' => 'university-of-nairobi-connectivity-upgrade',
            'meta_description' => 'Campus-wide Starlink rollout for resilient connectivity and faster access.',
            'image_alt_text' => 'University of Nairobi Starlink installation',
            'heading_2' => 'Education sector',
            'type' => 'Post',
            'image_path' => 'pages/university-of-nairobi.jpg',
            'page_description' => '<p>Detailed installation story for the University of Nairobi deployment.</p>',
        ]);

        SitePage::query()->create([
            'page_title' => 'Ocean Beach Hotel Guest Wi-Fi Expansion',
            'slug' => 'ocean-beach-hotel-guest-wifi-expansion',
            'meta_description' => 'Hospitality deployment with stronger guest Wi-Fi coverage and dependable uptime.',
            'image_alt_text' => 'Ocean Beach Hotel connectivity project',
            'heading_2' => 'Hospitality project',
            'type' => 'Post',
            'image_path' => 'pages/ocean-beach-hotel.jpg',
            'page_description' => '<p>Detailed hospitality rollout story.</p>',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Case Studies');
        $response->assertSeeText("Where We've Delivered Connectivity: Trusted by Organizations Nationwide.");
        $response->assertSeeText('University of Nairobi Connectivity Upgrade');
        $response->assertSeeText('Ocean Beach Hotel Guest Wi-Fi Expansion');
        $response->assertSee(route('site-pages.show', ['page' => 'university-of-nairobi-connectivity-upgrade']), false);
        $response->assertSee(route('media.show', ['path' => 'pages/university-of-nairobi.jpg']), false);
        $response->assertSeeText('Read more');
    }
}

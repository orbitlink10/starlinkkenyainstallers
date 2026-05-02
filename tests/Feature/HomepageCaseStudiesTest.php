<?php

namespace Tests\Feature;

use App\Models\HomepageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageCaseStudiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_case_study_cards_from_homepage_content(): void
    {
        HomepageContent::query()->create([
            'hero_header_title' => 'Starlink Kenya',
            'case_studies' => [
                [
                    'label' => 'Education sector',
                    'title' => 'University of Nairobi Connectivity Upgrade',
                    'excerpt' => 'Campus-wide Starlink rollout for resilient connectivity and faster access.',
                    'href' => '/university-of-nairobi-connectivity-upgrade',
                    'image_path' => 'homepage/case-studies/university-of-nairobi.jpg',
                    'image_alt' => 'University of Nairobi Starlink installation',
                ],
                [
                    'label' => 'Healthcare project',
                    'title' => 'Kenyatta National Hospital',
                    'excerpt' => 'Hospital deployment with stable internet for day-to-day operations.',
                    'href' => '/kenyatta-national-hospital',
                    'image_path' => 'homepage/case-studies/kenyatta-national-hospital.jpg',
                    'image_alt' => 'Kenyatta National Hospital connectivity project',
                ],
                [
                    'label' => 'County coverage',
                    'title' => 'Makindu Sub County Hospital',
                    'excerpt' => 'Reliable connectivity for a remote healthcare environment.',
                    'href' => '/makindu-sub-county-hospital',
                    'image_path' => 'homepage/case-studies/makindu-sub-county-hospital.jpg',
                    'image_alt' => 'Makindu connectivity deployment',
                ],
                [
                    'label' => 'Hospitality project',
                    'title' => 'Ocean Beach Hotel',
                    'excerpt' => 'Guest Wi-Fi expansion and better uptime for hospitality teams.',
                    'href' => '/ocean-beach-hotel',
                    'image_path' => 'homepage/case-studies/ocean-beach-hotel.jpg',
                    'image_alt' => 'Ocean Beach Hotel connectivity project',
                ],
            ],
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Case Studies');
        $response->assertSeeText("Where We've Delivered Connectivity: Trusted by Organizations Nationwide.");
        $response->assertSeeText('University of Nairobi Connectivity Upgrade');
        $response->assertSeeText('Ocean Beach Hotel');
        $response->assertSee('/university-of-nairobi-connectivity-upgrade', false);
        $response->assertSee(route('media.show', ['path' => 'homepage/case-studies/university-of-nairobi.jpg']), false);
        $response->assertSeeText('Read more');
    }

    public function test_admin_can_update_homepage_case_studies_from_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.homepage-content.update'), [
            'hero_header_title' => 'Starlink Kenya',
            'hero_header_description' => 'Fast satellite internet.',
            'why_choose_title' => 'Why choose Starlink',
            'why_choose_description' => 'Reliable setup and support.',
            'products_section_title' => 'Featured kits',
            'home_page_content' => '<h2>Guide</h2><p>Content</p>',
            'case_studies' => [
                [
                    'label' => 'Education sector',
                    'title' => 'University of Nairobi',
                    'excerpt' => 'Campus deployment for resilient internet access.',
                    'href' => '/university-of-nairobi',
                    'image_alt' => 'University of Nairobi deployment',
                ],
                [
                    'label' => 'Healthcare project',
                    'title' => 'Kenyatta National Hospital',
                    'excerpt' => 'Hospital deployment for reliable operations.',
                    'href' => '/kenyatta-national-hospital',
                    'image_alt' => 'Kenyatta National Hospital deployment',
                ],
                [
                    'label' => 'County coverage',
                    'title' => 'Makindu Sub County Hospital',
                    'excerpt' => 'Remote healthcare connectivity rollout.',
                    'href' => '/makindu-sub-county-hospital',
                    'image_alt' => 'Makindu hospital deployment',
                ],
                [
                    'label' => 'Hospitality project',
                    'title' => 'Ocean Beach Hotel',
                    'excerpt' => 'Guest Wi-Fi and stable backhaul for hospitality operations.',
                    'href' => '/ocean-beach-hotel',
                    'image_alt' => 'Ocean Beach Hotel deployment',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.section', ['section' => 'homepage-content']));
        $response->assertSessionHas('success', 'Homepage content saved successfully.');

        $this->assertSame('University of Nairobi', HomepageContent::query()->first()?->case_studies[0]['title']);
        $this->assertSame('/ocean-beach-hotel', HomepageContent::query()->first()?->case_studies[3]['href']);
    }
}

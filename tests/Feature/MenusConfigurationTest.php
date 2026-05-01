<?php

namespace Tests\Feature;

use App\Models\HomepageContent;
use App\Models\SitePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenusConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_navigation_menu_items(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.menus.update'), [
            'navigation_menu' => [
                ['label' => 'Shop', 'href' => '#packages'],
                ['label' => 'Cart', 'href' => '/cart'],
                ['label' => 'Installers', 'href' => '/#installation'],
            ],
        ]);

        $response->assertRedirect(route('admin.section', ['section' => 'menus']));
        $response->assertSessionHas('success', 'Menus updated successfully.');

        $this->assertSame([
            ['label' => 'Shop', 'href' => '#packages'],
            ['label' => 'Cart', 'href' => '/cart'],
            ['label' => 'Installers', 'href' => '/#installation'],
        ], HomepageContent::query()->first()?->navigation_menu);
    }

    public function test_saved_navigation_menu_renders_on_home_and_page_views(): void
    {
        HomepageContent::query()->create([
            'hero_header_title' => 'Starlink Kenya',
            'navigation_menu' => [
                ['label' => 'Shop', 'href' => '#packages'],
                ['label' => 'Cart', 'href' => '/cart'],
                ['label' => 'Installation Help', 'href' => '/#installation'],
            ],
        ]);

        SitePage::query()->create([
            'page_title' => 'Starlink in Kenya',
            'slug' => 'starlink-in-kenya',
            'type' => 'Post',
        ]);

        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSeeText('Shop');
        $homeResponse->assertSeeText('Cart');
        $homeResponse->assertSee('href="#packages"', false);
        $homeResponse->assertSee('href="/cart"', false);

        $pageResponse = $this->get('/starlink-in-kenya');
        $pageResponse->assertOk();
        $pageResponse->assertSeeText('Installation Help');
        $pageResponse->assertSee('href="http://localhost#packages"', false);
        $pageResponse->assertSee('href="/cart"', false);
    }
}

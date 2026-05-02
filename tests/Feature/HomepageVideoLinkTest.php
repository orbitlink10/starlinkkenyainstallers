<?php

namespace Tests\Feature;

use App\Models\HomepageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomepageVideoLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_youtube_video_link_for_homepage_content(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.homepage-content.update'), [
            'hero_header_title' => 'Starlink Kenya',
            'hero_header_description' => 'Fast satellite internet.',
            'why_choose_title' => 'Why choose Starlink',
            'why_choose_description' => 'Reliable setup and support.',
            'youtube_video_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'products_section_title' => 'Featured kits',
            'home_page_content' => '<h2>Guide</h2><p>Content</p>',
        ]);

        $response->assertRedirect(route('admin.section', ['section' => 'homepage-content']));
        $response->assertSessionHas('success', 'Homepage content saved successfully.');

        $this->assertSame(
            'https://youtu.be/dQw4w9WgXcQ',
            HomepageContent::query()->first()?->youtube_video_url
        );
    }

    public function test_admin_homepage_content_rejects_invalid_youtube_video_link(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('admin.section', ['section' => 'homepage-content']))
            ->actingAs($user)
            ->post(route('admin.homepage-content.update'), [
                'hero_header_title' => 'Starlink Kenya',
                'youtube_video_url' => 'https://example.com/not-youtube',
            ]);

        $response->assertRedirect(route('admin.section', ['section' => 'homepage-content']));
        $response->assertSessionHasErrors([
            'youtube_video_url' => 'Enter a valid YouTube link or 11-character video ID.',
        ]);
    }

    public function test_homepage_renders_saved_youtube_video_as_embed_url(): void
    {
        HomepageContent::query()->create([
            'hero_header_title' => 'Starlink Kenya',
            'youtube_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('src="https://www.youtube.com/embed/dQw4w9WgXcQ"', false);
    }

    public function test_homepage_editor_shows_migration_notice_when_youtube_column_is_missing(): void
    {
        Schema::table('homepage_contents', function (Blueprint $table): void {
            $table->dropColumn('youtube_video_url');
        });

        HomepageContent::query()->create([
            'hero_header_title' => 'Starlink Kenya',
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.section', ['section' => 'homepage-content']));

        $response->assertOk();
        $response->assertSeeText('Run the latest database migration to enable the homepage YouTube link field.');
        $response->assertDontSee('name="youtube_video_url"', false);
    }
}

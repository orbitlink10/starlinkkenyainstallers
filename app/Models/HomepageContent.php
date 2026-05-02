<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    private const DEFAULT_YOUTUBE_VIDEO_ID = 'ZBpsEnxmsG4';

    protected $fillable = [
        'hero_header_title',
        'hero_header_description',
        'hero_image_path',
        'why_choose_title',
        'why_choose_description',
        'youtube_video_url',
        'products_section_title',
        'home_page_content',
        'navigation_menu',
    ];

    protected function casts(): array
    {
        return [
            'navigation_menu' => 'array',
        ];
    }

    /**
     * @return array<int, array{label:string, href:string}>
     */
    public static function defaultNavigationMenu(): array
    {
        return [
            ['label' => 'Shop', 'href' => '#packages'],
            ['label' => 'Starlink Kenya Prices', 'href' => '#prices'],
            ['label' => 'Installation', 'href' => '#installation'],
            ['label' => 'Coverage', 'href' => '#coverage'],
            ['label' => 'FAQs', 'href' => '#faqs'],
        ];
    }

    /**
     * @param  array<int, mixed>|null  $items
     * @return array<int, array{label:string, href:string}>
     */
    public static function normalizeNavigationMenu(?array $items): array
    {
        $normalized = collect($items ?? [])
            ->map(function ($item): ?array {
                if (! is_array($item)) {
                    return null;
                }

                $label = trim((string) ($item['label'] ?? ''));
                $href = trim((string) ($item['href'] ?? ''));

                if ($label === '' || $href === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'href' => $href,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return $normalized !== [] ? $normalized : static::defaultNavigationMenu();
    }

    /**
     * @return array<int, array{label:string, href:string}>
     */
    public static function currentNavigationMenu(): array
    {
        return static::normalizeNavigationMenu(static::query()->first()?->navigation_menu);
    }

    public static function defaultYoutubeVideoUrl(): string
    {
        return 'https://www.youtube.com/watch?v='.self::DEFAULT_YOUTUBE_VIDEO_ID;
    }

    public static function defaultYoutubeEmbedUrl(): string
    {
        return 'https://www.youtube.com/embed/'.self::DEFAULT_YOUTUBE_VIDEO_ID;
    }

    public function youtubeEmbedUrl(): ?string
    {
        return static::youtubeEmbedUrlFromInput($this->youtube_video_url);
    }

    public static function youtubeEmbedUrlFromInput(?string $value): ?string
    {
        $videoId = static::extractYoutubeVideoId($value);

        return $videoId !== null
            ? 'https://www.youtube.com/embed/'.$videoId
            : null;
    }

    public static function extractYoutubeVideoId(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $value) === 1) {
            return $value;
        }

        if (! preg_match('#^https?://#i', $value)) {
            $value = 'https://'.$value;
        }

        $parts = parse_url($value);

        if (! is_array($parts)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));

        if ($host === '') {
            return null;
        }

        $host = preg_replace('/^www\./', '', $host) ?? $host;
        $path = trim((string) ($parts['path'] ?? ''), '/');
        $segments = $path === '' ? [] : explode('/', $path);
        $candidate = null;

        parse_str((string) ($parts['query'] ?? ''), $query);

        if ($host === 'youtu.be') {
            $candidate = $segments[0] ?? null;
        } elseif (in_array($host, ['youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtube-nocookie.com'], true)) {
            if (($segments[0] ?? null) === 'watch') {
                $candidate = $query['v'] ?? null;
            } elseif (in_array($segments[0] ?? null, ['embed', 'shorts', 'live', 'v'], true)) {
                $candidate = $segments[1] ?? null;
            }
        }

        return is_string($candidate) && preg_match('/^[A-Za-z0-9_-]{11}$/', $candidate) === 1
            ? $candidate
            : null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = [
        'hero_header_title',
        'hero_header_description',
        'hero_image_path',
        'why_choose_title',
        'why_choose_description',
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
            ['label' => 'Starlink Kenya Packages', 'href' => '#packages'],
            ['label' => 'Starlink Kenya Prices', 'href' => '#prices'],
            ['label' => 'Order Now', 'href' => '#order-now'],
            ['label' => 'Installation', 'href' => '#installation'],
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
}

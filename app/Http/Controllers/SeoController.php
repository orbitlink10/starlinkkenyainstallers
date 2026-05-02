<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;
use App\Models\Product;
use App\Models\SitePage;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
        ];

        foreach (config('seo.robots_disallow', []) as $path) {
            $lines[] = 'Disallow: '.$path;
        }

        $lines[] = 'Sitemap: '.route('seo.sitemap');

        return response(implode("\n", $lines)."\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(): Response
    {
        $homeUpdatedAt = HomepageContent::query()->value('updated_at') ?? now();
        $products = Product::query()
            ->where('is_active', true)
            ->orderByDesc('updated_at')
            ->get(['id', 'slug', 'updated_at']);
        $pages = SitePage::query()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        return response()
            ->view('seo.sitemap', compact('homeUpdatedAt', 'products', 'pages'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}

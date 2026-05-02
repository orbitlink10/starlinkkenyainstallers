<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use App\Services\AnalyticsService;
use App\Support\SeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SitePageController extends Controller
{
    public function show(SitePage $page, Request $request, AnalyticsService $analyticsService): View
    {
        $analyticsService->trackPageView($request, $page->page_title, 'page', [
            'page_id' => $page->id,
            'slug' => $page->slug,
            'type' => $page->type,
        ]);

        $page->setAttribute(
            'page_description',
            SeoData::sanitizeCommercialLinks((string) $page->page_description)
        );

        $pageUrl = route('site-pages.show', ['page' => $page->slug]);
        $pageImageUrl = SeoData::mediaUrl($page->image_path);
        $pageDescription = trim((string) $page->meta_description);

        if ($pageDescription === '') {
            $pageDescription = SeoData::trimDescription($page->page_description, 170);
        }

        $robots = in_array($page->slug, config('seo.noindex_page_slugs', []), true)
            ? 'noindex,follow'
            : 'index,follow';

        return view('pages.show', [
            'page' => $page,
            'seo' => [
                'title' => $page->meta_title ?: $page->page_title.' | Starlink Kenya',
                'description' => $pageDescription,
                'canonical' => $pageUrl,
                'robots' => $robots,
                'type' => strtolower((string) $page->type) === 'post' ? 'article' : 'website',
                'image' => $pageImageUrl,
                'schema' => [
                    SeoData::breadcrumbSchema([
                        ['name' => 'Home', 'url' => route('home')],
                        ['name' => Str::headline((string) $page->type ?: 'Pages'), 'url' => route('home').'#faqs'],
                        ['name' => $page->page_title, 'url' => $pageUrl],
                    ]),
                    SeoData::pageSchema($page, $pageUrl, SeoData::trimDescription($pageDescription), $pageImageUrl),
                ],
            ],
        ]);
    }
}

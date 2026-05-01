<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
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

        return view('pages.show', [
            'page' => $page,
        ]);
    }
}

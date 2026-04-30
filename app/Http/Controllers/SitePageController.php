<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use Illuminate\View\View;

class SitePageController extends Controller
{
    public function show(SitePage $page): View
    {
        return view('pages.show', [
            'page' => $page,
        ]);
    }
}

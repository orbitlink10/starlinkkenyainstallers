<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request, AnalyticsService $analyticsService): View
    {
        $range = (int) $request->integer('range', 30);
        $report = $analyticsService->buildDashboardReport($range);

        return view('dashboard.analytics', [
            'activeSection' => 'analytics',
            'report' => $report,
        ]);
    }
}

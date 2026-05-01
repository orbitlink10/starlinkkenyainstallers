<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\Enquiry;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AnalyticsService
{
    private const VISITOR_COOKIE = 'starlink_visitor_id';

    /**
     * @param  array<string, mixed>  $properties
     */
    public function trackPageView(Request $request, string $label, string $pageType, array $properties = []): void
    {
        $this->store(
            request: $request,
            eventType: 'page_view',
            label: $label,
            pageType: $pageType,
            path: $this->normalizePath($request),
            properties: $properties,
        );
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function trackEvent(
        Request $request,
        string $eventType,
        ?string $label = null,
        ?string $pageType = null,
        array $properties = [],
        ?string $path = null,
    ): void {
        $this->store(
            request: $request,
            eventType: $eventType,
            label: $label,
            pageType: $pageType,
            path: $path ?? $this->normalizePath($request),
            properties: $properties,
        );
    }

    /**
     * @return array{
     *     range:int,
     *     start:\Illuminate\Support\Carbon,
     *     end:\Illuminate\Support\Carbon,
     *     firstTrackedVisit:\Illuminate\Support\Carbon|null,
     *     pageViews:int,
     *     uniqueVisitors:int,
     *     trackedPages:int,
     *     pagesPerVisitor:float,
     *     productViews:int,
     *     searches:int,
     *     cartActions:int,
     *     leadActions:int,
     *     trend:\Illuminate\Support\Collection<int, array{date:\Illuminate\Support\Carbon, dayLabel:string, views:int, height:int, tooltip:string}>,
     *     topPages:\Illuminate\Support\Collection<int, array{label:string, path:string, pageType:?string, views:int, visitors:int, lastSeen:\Illuminate\Support\Carbon}>,
     *     recentVisits:\Illuminate\Support\Collection<int, array{label:string, path:string, referrer:string, occurredAt:\Illuminate\Support\Carbon}>,
     *     topReferrers:\Illuminate\Support\Collection<int, array{host:string, views:int, visitors:int}>,
     *     topProducts:\Illuminate\Support\Collection<int, array{label:string, path:string, views:int, visitors:int, lastSeen:\Illuminate\Support\Carbon}>,
     *     topSearches:\Illuminate\Support\Collection<int, array{query:string, searches:int, visitors:int, lastSeen:\Illuminate\Support\Carbon}>,
     *     conversions:array{
     *         orders:int,
     *         paidOrders:int,
     *         invoices:int,
     *         enquiries:int,
     *         revenue:float
     *     }
     * }
     */
    public function buildDashboardReport(int $range = 30): array
    {
        $range = in_array($range, [7, 30, 90], true) ? $range : 30;
        $end = now()->endOfDay();
        $start = now()->startOfDay()->subDays($range - 1);

        $pageViewsQuery = $this->pageViewsWithin($start, $end);
        $pageViews = (clone $pageViewsQuery)->count();
        $uniqueVisitors = (clone $pageViewsQuery)->distinct()->count('visitor_id');
        $trackedPages = (clone $pageViewsQuery)->distinct()->count('path');
        $productViews = (clone $pageViewsQuery)->where('page_type', 'product')->count();
        $searches = $this->eventCount('search', $start, $end);
        $cartActions = $this->eventCount('add_to_cart', $start, $end);

        $orders = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $enquiries = Enquiry::query()
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $paidOrdersQuery = Order::query()
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$start, $end]);

        $leadActions = $cartActions + $enquiries + $orders;

        return [
            'range' => $range,
            'start' => $start,
            'end' => $end,
            'firstTrackedVisit' => AnalyticsEvent::query()
                ->where('event_type', 'page_view')
                ->oldest('occurred_at')
                ->first()?->occurred_at,
            'pageViews' => $pageViews,
            'uniqueVisitors' => $uniqueVisitors,
            'trackedPages' => $trackedPages,
            'pagesPerVisitor' => $uniqueVisitors > 0 ? round($pageViews / $uniqueVisitors, 1) : 0.0,
            'productViews' => $productViews,
            'searches' => $searches,
            'cartActions' => $cartActions,
            'leadActions' => $leadActions,
            'trend' => $this->trafficTrend($start, $end),
            'topPages' => $this->topPages($start, $end),
            'recentVisits' => $this->recentVisits($start, $end),
            'topReferrers' => $this->topReferrers($start, $end),
            'topProducts' => $this->topProducts($start, $end),
            'topSearches' => $this->topSearches($start, $end),
            'conversions' => [
                'orders' => $orders,
                'paidOrders' => (clone $paidOrdersQuery)->count(),
                'invoices' => Invoice::query()
                    ->whereBetween('issued_at', [$start->toDateString(), $end->toDateString()])
                    ->count(),
                'enquiries' => $enquiries,
                'revenue' => (float) (clone $paidOrdersQuery)->sum('amount'),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    private function store(
        Request $request,
        string $eventType,
        ?string $label,
        ?string $pageType,
        ?string $path,
        array $properties,
    ): void {
        AnalyticsEvent::query()->create([
            'event_type' => $eventType,
            'visitor_id' => $this->visitorId($request),
            'path' => $path,
            'label' => $label,
            'page_type' => $pageType,
            'referrer_host' => $this->externalReferrerHost($request),
            'referrer_url' => $this->externalReferrerUrl($request),
            'properties' => $properties === [] ? null : $properties,
            'occurred_at' => now(),
        ]);
    }

    private function visitorId(Request $request): string
    {
        $visitorId = trim((string) $request->cookie(self::VISITOR_COOKIE));

        if ($visitorId !== '') {
            return $visitorId;
        }

        $visitorId = (string) Str::uuid();
        Cookie::queue(Cookie::forever(self::VISITOR_COOKIE, $visitorId));

        return $visitorId;
    }

    private function normalizePath(Request $request): string
    {
        $path = $request->getPathInfo();

        return $path !== '' ? $path : '/';
    }

    private function externalReferrerHost(Request $request): ?string
    {
        $referrer = $request->headers->get('referer');

        if (! is_string($referrer) || trim($referrer) === '') {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return null;
        }

        return strcasecmp($host, $request->getHost()) === 0 ? null : Str::lower($host);
    }

    private function externalReferrerUrl(Request $request): ?string
    {
        $referrer = $request->headers->get('referer');

        if (! is_string($referrer) || trim($referrer) === '') {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        if (! is_string($host) || $host === '' || strcasecmp($host, $request->getHost()) === 0) {
            return null;
        }

        return $referrer;
    }

    private function eventCount(string $eventType, Carbon $start, Carbon $end): int
    {
        return AnalyticsEvent::query()
            ->where('event_type', $eventType)
            ->whereBetween('occurred_at', [$start, $end])
            ->count();
    }

    private function pageViewsWithin(Carbon $start, Carbon $end): Builder
    {
        return AnalyticsEvent::query()
            ->where('event_type', 'page_view')
            ->whereBetween('occurred_at', [$start, $end]);
    }

    /**
     * @return Collection<int, array{date:Carbon, dayLabel:string, views:int, height:int, tooltip:string}>
     */
    private function trafficTrend(Carbon $start, Carbon $end): Collection
    {
        $series = $this->pageViewsWithin($start, $end)
            ->selectRaw('DATE(occurred_at) as visit_date, COUNT(*) as total_views')
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->pluck('total_views', 'visit_date');

        $days = collect();
        $cursor = $start->copy()->startOfDay();

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $days->push([
                'date' => $cursor->copy(),
                'views' => (int) ($series[$key] ?? 0),
            ]);
            $cursor->addDay();
        }

        $maxViews = max(1, (int) $days->max('views'));

        return $days->map(function (array $row) use ($maxViews): array {
            $date = $row['date'];
            $views = $row['views'];

            return [
                'date' => $date,
                'dayLabel' => $date->format('d'),
                'views' => $views,
                'height' => max(12, (int) round(($views / $maxViews) * 196)),
                'tooltip' => sprintf('%s: %d views', $date->format('d M Y'), $views),
            ];
        });
    }

    /**
     * @return Collection<int, array{label:string, path:string, pageType:?string, views:int, visitors:int, lastSeen:Carbon}>
     */
    private function topPages(Carbon $start, Carbon $end): Collection
    {
        return $this->pageViewsWithin($start, $end)
            ->selectRaw('COALESCE(label, path, "/") as event_label')
            ->selectRaw('COALESCE(path, "/") as event_path')
            ->selectRaw('page_type')
            ->selectRaw('COUNT(*) as total_views')
            ->selectRaw('COUNT(DISTINCT visitor_id) as total_visitors')
            ->selectRaw('MAX(occurred_at) as last_seen_at')
            ->groupBy('event_label', 'event_path', 'page_type')
            ->orderByDesc('total_views')
            ->orderByDesc('last_seen_at')
            ->limit(8)
            ->get()
            ->map(fn ($row): array => [
                'label' => (string) $row->event_label,
                'path' => (string) $row->event_path,
                'pageType' => $row->page_type ? (string) $row->page_type : null,
                'views' => (int) $row->total_views,
                'visitors' => (int) $row->total_visitors,
                'lastSeen' => Carbon::parse($row->last_seen_at),
            ]);
    }

    /**
     * @return Collection<int, array{label:string, path:string, referrer:string, occurredAt:Carbon}>
     */
    private function recentVisits(Carbon $start, Carbon $end): Collection
    {
        return $this->pageViewsWithin($start, $end)
            ->latest('occurred_at')
            ->limit(10)
            ->get()
            ->map(fn (AnalyticsEvent $event): array => [
                'label' => $event->label ?: ($event->path ?: '/'),
                'path' => $event->path ?: '/',
                'referrer' => $event->referrer_host ?: 'Direct',
                'occurredAt' => $event->occurred_at,
            ]);
    }

    /**
     * @return Collection<int, array{host:string, views:int, visitors:int}>
     */
    private function topReferrers(Carbon $start, Carbon $end): Collection
    {
        return $this->pageViewsWithin($start, $end)
            ->whereNotNull('referrer_host')
            ->selectRaw('referrer_host, COUNT(*) as total_views, COUNT(DISTINCT visitor_id) as total_visitors')
            ->groupBy('referrer_host')
            ->orderByDesc('total_views')
            ->limit(8)
            ->get()
            ->map(fn ($row): array => [
                'host' => (string) $row->referrer_host,
                'views' => (int) $row->total_views,
                'visitors' => (int) $row->total_visitors,
            ]);
    }

    /**
     * @return Collection<int, array{label:string, path:string, views:int, visitors:int, lastSeen:Carbon}>
     */
    private function topProducts(Carbon $start, Carbon $end): Collection
    {
        return $this->pageViewsWithin($start, $end)
            ->where('page_type', 'product')
            ->selectRaw('COALESCE(label, path, "/") as event_label')
            ->selectRaw('COALESCE(path, "/") as event_path')
            ->selectRaw('COUNT(*) as total_views')
            ->selectRaw('COUNT(DISTINCT visitor_id) as total_visitors')
            ->selectRaw('MAX(occurred_at) as last_seen_at')
            ->groupBy('event_label', 'event_path')
            ->orderByDesc('total_views')
            ->orderByDesc('last_seen_at')
            ->limit(6)
            ->get()
            ->map(fn ($row): array => [
                'label' => (string) $row->event_label,
                'path' => (string) $row->event_path,
                'views' => (int) $row->total_views,
                'visitors' => (int) $row->total_visitors,
                'lastSeen' => Carbon::parse($row->last_seen_at),
            ]);
    }

    /**
     * @return Collection<int, array{query:string, searches:int, visitors:int, lastSeen:Carbon}>
     */
    private function topSearches(Carbon $start, Carbon $end): Collection
    {
        return AnalyticsEvent::query()
            ->where('event_type', 'search')
            ->whereBetween('occurred_at', [$start, $end])
            ->whereNotNull('label')
            ->where('label', '!=', '')
            ->selectRaw('label as search_query')
            ->selectRaw('COUNT(*) as total_searches')
            ->selectRaw('COUNT(DISTINCT visitor_id) as total_visitors')
            ->selectRaw('MAX(occurred_at) as last_seen_at')
            ->groupBy('search_query')
            ->orderByDesc('total_searches')
            ->orderByDesc('last_seen_at')
            ->limit(6)
            ->get()
            ->map(fn ($row): array => [
                'query' => (string) $row->search_query,
                'searches' => (int) $row->total_searches,
                'visitors' => (int) $row->total_visitors,
                'lastSeen' => Carbon::parse($row->last_seen_at),
            ]);
    }
}

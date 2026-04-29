<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        $stats = [
            'activeProducts' => $products->count(),
            'ordersCompleted' => Order::query()->where('status', 'completed')->count(),
            'coverageCounties' => 47,
            'supportHours' => '08:00 - 20:00',
        ];

        $homepageContent = HomepageContent::query()->first();
        $defaultHomeContent = '<h2>Starlink Kenya: A Comprehensive Guide to Satellite Internet Connectivity</h2><p>Explore STARLINK KENYA, the satellite internet service transforming digital access across Kenya.</p>';
        $homePageContentHtml = $this->formatHomePageContent($homepageContent?->home_page_content ?: $defaultHomeContent);

        return view('home.index', compact('products', 'stats', 'homepageContent', 'homePageContentHtml'));
    }

    private function formatHomePageContent(?string $content): string
    {
        $content = trim((string) $content);

        if ($content === '') {
            return '';
        }

        // Respect already-formatted HTML content from admin.
        if (preg_match('/<\s*(h[1-6]|p|ul|ol|li|blockquote|div|section|article)\b/i', $content)) {
            return $content;
        }

        $text = preg_replace("/\r\n?/", "\n", $content);
        $lines = array_map('trim', explode("\n", (string) $text));

        $html = [];
        $listItems = [];
        $firstHeadingRendered = false;
        $previousEndedWithColon = false;

        $flushList = function () use (&$html, &$listItems): void {
            if ($listItems === []) {
                return;
            }

            $items = array_map(fn (string $item): string => '<li>'.e($item).'</li>', $listItems);
            $html[] = '<ul>'.implode('', $items).'</ul>';
            $listItems = [];
        };

        foreach ($lines as $line) {
            if ($line === '') {
                $flushList();
                $previousEndedWithColon = false;
                continue;
            }

            $isNumberedHeading = preg_match('/^\d+\.\s+.+$/', $line) === 1;

            if (! $firstHeadingRendered) {
                $flushList();
                $html[] = '<h2>'.e($line).'</h2>';
                $firstHeadingRendered = true;
                $previousEndedWithColon = false;
                continue;
            }

            if ($isNumberedHeading) {
                $flushList();
                $html[] = '<h2>'.e($line).'</h2>';
                $previousEndedWithColon = false;
                continue;
            }

            $lineLooksLikeListItem = $previousEndedWithColon
                && strlen($line) <= 120
                && ! Str::endsWith($line, ['.', '!', '?', ':']);

            if ($lineLooksLikeListItem) {
                $listItems[] = $line;
                continue;
            }

            $flushList();
            $html[] = '<p>'.e($line).'</p>';
            $previousEndedWithColon = Str::endsWith($line, ':');
        }

        $flushList();

        return implode('', $html);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $searchQuery = trim((string) $request->string('q'));

        $activeProductsQuery = Product::query()
            ->where('is_active', true);

        $products = (clone $activeProductsQuery)
            ->when($searchQuery !== '', function (Builder $query) use ($searchQuery): Builder {
                return $query->where(function (Builder $productQuery) use ($searchQuery): Builder {
                    return $productQuery
                        ->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('slug', 'like', "%{$searchQuery}%")
                        ->orWhere('meta_description', 'like', "%{$searchQuery}%")
                        ->orWhere('description', 'like', "%{$searchQuery}%");
                });
            })
            ->orderBy('price')
            ->get();

        $stats = [
            'activeProducts' => (clone $activeProductsQuery)->count(),
            'ordersCompleted' => Order::query()->where('status', 'completed')->count(),
            'coverageCounties' => 47,
            'supportHours' => '08:00 - 20:00',
        ];

        $homepageContent = HomepageContent::query()->first();
        $defaultHomeContent = '<h2>Starlink Kenya: A Comprehensive Guide to Satellite Internet Connectivity</h2><p>Explore STARLINK KENYA, the satellite internet service transforming digital access across Kenya.</p>';
        $homePageContentHtml = $this->formatHomePageContent($homepageContent?->home_page_content ?: $defaultHomeContent);

        return view('home.index', compact('products', 'stats', 'homepageContent', 'homePageContentHtml', 'searchQuery'));
    }

    private function formatHomePageContent(?string $content): string
    {
        $content = trim((string) $content);

        if ($content === '') {
            return '';
        }

        if ($this->containsHtmlMarkup($content)) {
            return $content;
        }

        $text = preg_replace("/\r\n?/", "\n", $content) ?? $content;
        $lines = array_map(
            static fn (string $line): string => trim(preg_replace('/\s+/u', ' ', $line) ?? $line),
            explode("\n", $text)
        );

        $html = [];
        $listItems = [];
        $firstHeadingRendered = false;
        $previousEndedWithColon = false;
        $lineCount = count($lines);

        $flushList = function () use (&$html, &$listItems): void {
            if ($listItems === []) {
                return;
            }

            $items = array_map(fn (string $item): string => '<li>'.e($item).'</li>', $listItems);
            $html[] = '<ul>'.implode('', $items).'</ul>';
            $listItems = [];
        };

        foreach ($lines as $index => $line) {
            if ($line === '') {
                if ($previousEndedWithColon || $listItems !== []) {
                    continue;
                }

                $flushList();
                continue;
            }

            $markdownHeading = $this->extractMarkdownHeading($line);

            if ($markdownHeading !== null) {
                $flushList();
                $html[] = $this->renderHeading($markdownHeading['level'], $markdownHeading['text']);
                $firstHeadingRendered = true;
                $previousEndedWithColon = false;
                continue;
            }

            if (! $firstHeadingRendered) {
                $flushList();
                $html[] = '<h2>'.e($line).'</h2>';
                $firstHeadingRendered = true;
                $previousEndedWithColon = false;
                continue;
            }

            if (preg_match('/^\d+\.\s+.+$/u', $line) === 1) {
                $flushList();
                $html[] = '<h2>'.e($line).'</h2>';
                $previousEndedWithColon = false;
                continue;
            }

            if (preg_match('/^[A-Z]\.\s+.+$/u', $line) === 1) {
                $flushList();
                $html[] = '<h3>'.e($line).'</h3>';
                $previousEndedWithColon = false;
                continue;
            }

            if ($this->hasExplicitListMarker($line)) {
                $listItems[] = $this->normalizeListItem($line);
                $previousEndedWithColon = false;
                continue;
            }

            $nextLine = $this->nextNonEmptyLine($lines, $lineCount, $index + 1);

            if (($listItems === [] || $this->looksLikeEditorialSubheading($line))
                && $this->looksLikeStandaloneSubheading($line, $nextLine)) {
                $flushList();
                $html[] = '<h3>'.e($line).'</h3>';
                $previousEndedWithColon = false;
                continue;
            }

            if ($previousEndedWithColon && $this->looksLikeListItem($line)) {
                $listItems[] = $this->normalizeListItem($line);
                continue;
            }

            $flushList();
            $html[] = '<p>'.e($line).'</p>';
            $previousEndedWithColon = Str::endsWith($line, ':');
        }

        $flushList();

        return implode('', $html);
    }

    private function containsHtmlMarkup(string $content): bool
    {
        return preg_match('/<\s*(h[1-6]|p|ul|ol|li|blockquote|div|section|article|table|figure|img|br)\b/i', $content) === 1;
    }

    /**
     * @return array{level:int, text:string}|null
     */
    private function extractMarkdownHeading(string $line): ?array
    {
        if (preg_match('/^(#{1,6})\s+(.+)$/u', $line, $matches) !== 1) {
            return null;
        }

        return [
            'level' => max(1, min(4, strlen($matches[1]))),
            'text' => trim($matches[2]),
        ];
    }

    private function renderHeading(int $level, string $text): string
    {
        $level = max(1, min(4, $level));

        return sprintf('<h%d>%s</h%d>', $level, e($text), $level);
    }

    private function hasExplicitListMarker(string $line): bool
    {
        return preg_match('/^(?:[-*•])\s+.+$/u', $line) === 1;
    }

    private function looksLikeListItem(string $line): bool
    {
        if (preg_match('/^(?:\d+\.\s+|[A-Z]\.\s+)/u', $line) === 1) {
            return false;
        }

        if (preg_match('/[.!?:]$/u', $line) === 1) {
            return false;
        }

        return mb_strlen($line) <= 110;
    }

    private function normalizeListItem(string $line): string
    {
        return trim(preg_replace('/^(?:[-*•])\s+/u', '', $line) ?? $line);
    }

    /**
     * @param  array<int, string>  $lines
     */
    private function nextNonEmptyLine(array $lines, int $lineCount, int $startAt): ?string
    {
        for ($index = $startAt; $index < $lineCount; $index++) {
            if ($lines[$index] !== '') {
                return $lines[$index];
            }
        }

        return null;
    }

    private function looksLikeStandaloneSubheading(string $line, ?string $nextLine): bool
    {
        if ($nextLine === null) {
            return false;
        }

        if (preg_match('/[.!?]$/u', $line) === 1) {
            return false;
        }

        if (preg_match('/^(?:[-*•]|\d+\.\s+|[A-Z]\.\s+)/u', $line) === 1) {
            return false;
        }

        if (preg_match('/^[\pL\pN][\pL\pN\s&(),\'’\/:+-]*$/u', $line) !== 1) {
            return false;
        }

        $wordCount = count(array_filter(preg_split('/\s+/u', $line) ?: []));

        return $wordCount <= 8
            && mb_strlen($line) <= 72
            && (preg_match('/[.!?]$/u', $nextLine) === 1 || mb_strlen($nextLine) >= 70);
    }

    private function looksLikeEditorialSubheading(string $line): bool
    {
        return preg_match('/^(Final Thoughts|Conclusion|Summary|Key Takeaways)\b/i', $line) === 1;
    }
}

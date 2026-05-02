<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\HomepageContent;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminSectionController extends Controller
{
    public function show(string $section, Request $request): View
    {
        $sections = $this->sections();

        abort_unless(isset($sections[$section]), 404);

        $meta = $sections[$section];
        $table = $this->tableForSection($section, $request);
        $homepageContent = null;
        $menuItemsConfig = null;

        if (in_array($section, ['homepage-content', 'menus'], true)) {
            $homepageContent = $this->homepageContentRecord();
        }

        if ($section === 'menus' && $homepageContent) {
            $menuItemsConfig = HomepageContent::normalizeNavigationMenu(
                old('navigation_menu', $homepageContent->navigation_menu)
            );
        }

        return view('dashboard.section', [
            'activeSection' => $section,
            'section' => $section,
            'title' => $meta['title'],
            'description' => $meta['description'],
            'table' => $table,
            'homepageContent' => $homepageContent,
            'menuItemsConfig' => $menuItemsConfig,
        ]);
    }

    public function updateHomepageContent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_header_title' => ['required', 'string', 'max:255'],
            'hero_header_description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'why_choose_title' => ['nullable', 'string', 'max:255'],
            'why_choose_description' => ['nullable', 'string'],
            'youtube_video_url' => [
                'nullable',
                'string',
                'max:255',
                static function (string $attribute, mixed $value, \Closure $fail): void {
                    $youtubeVideoUrl = trim((string) $value);

                    if ($youtubeVideoUrl !== '' && HomepageContent::extractYoutubeVideoId($youtubeVideoUrl) === null) {
                        $fail('Enter a valid YouTube link or 11-character video ID.');
                    }
                },
            ],
            'products_section_title' => ['nullable', 'string', 'max:255'],
            'home_page_content' => ['nullable', 'string'],
        ]);

        $content = $this->homepageContentRecord();
        $validated['youtube_video_url'] = trim((string) ($validated['youtube_video_url'] ?? '')) ?: null;

        if ($request->hasFile('hero_image')) {
            if ($content->hero_image_path && Storage::disk('public')->exists($content->hero_image_path)) {
                Storage::disk('public')->delete($content->hero_image_path);
            }

            $validated['hero_image_path'] = $request->file('hero_image')->store('homepage', 'public');
        }

        unset($validated['hero_image']);

        $content->update($validated);

        return redirect()
            ->route('admin.section', ['section' => 'homepage-content'])
            ->with('success', 'Homepage content saved successfully.');
    }

    public function updateMenus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'navigation_menu' => ['required', 'array', 'max:10'],
            'navigation_menu.*.label' => ['nullable', 'string', 'max:80'],
            'navigation_menu.*.href' => ['nullable', 'string', 'max:255'],
        ]);

        $menuItems = collect($validated['navigation_menu'] ?? [])
            ->map(function (array $item): ?array {
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

        if ($menuItems === []) {
            return redirect()
                ->route('admin.section', ['section' => 'menus'])
                ->withErrors(['navigation_menu' => 'Add at least one menu item with both a label and a link.'])
                ->withInput();
        }

        $this->homepageContentRecord()->update([
            'navigation_menu' => $menuItems,
        ]);

        return redirect()
            ->route('admin.section', ['section' => 'menus'])
            ->with('success', 'Menus updated successfully.');
    }

    /**
     * @return array<string, array{title:string, description:string}>
     */
    private function sections(): array
    {
        return [
            'categories' => ['title' => 'Categories', 'description' => 'Organize products into clear storefront categories.'],
            'sub-categories' => ['title' => 'Sub Categories', 'description' => 'Manage nested product classifications for better browsing.'],
            'products' => ['title' => 'Products', 'description' => 'Manage Starlink kits and accessories listed on the website.'],
            'orders' => ['title' => 'Orders', 'description' => 'Track and manage incoming customer orders.'],
            'invoices' => ['title' => 'Invoices', 'description' => 'Review generated invoices and payment status.'],
            'enquiries' => ['title' => 'Enquiries', 'description' => 'Respond to sales and installation requests from visitors.'],
            'users' => ['title' => 'Users', 'description' => 'Manage admin and customer user accounts.'],
            'homepage-content' => ['title' => 'Homepage Content', 'description' => 'Edit text blocks and homepage messaging.'],
            'sliders' => ['title' => 'Sliders', 'description' => 'Manage homepage and campaign slider items.'],
            'pages' => ['title' => 'Pages', 'description' => 'Create and update custom website pages.'],
            'services' => ['title' => 'Services', 'description' => 'Define installation and networking service offerings.'],
            'testimonials' => ['title' => 'Testimonials', 'description' => 'Publish customer testimonials and success stories.'],
            'media' => ['title' => 'Media', 'description' => 'Upload and manage website images and files.'],
            'menus' => ['title' => 'Menus', 'description' => 'Configure website navigation menu items.'],
            'settings' => ['title' => 'Settings', 'description' => 'Update global system and website settings.'],
            'profile' => ['title' => 'Profile', 'description' => 'Manage your account profile details.'],
        ];
    }

    /**
     * @return array{headers: array<int, string>, rows: LengthAwarePaginator<array<string, mixed>>}|null
     */
    private function tableForSection(string $section, Request $request): ?array
    {
        return match ($section) {
            'products' => [
                'headers' => ['ID', 'Name', 'Price (KES)', 'Stock', 'Active', 'Created'],
                'rows' => Product::query()
                    ->latest('id')
                    ->paginate(12)
                    ->through(fn (Product $product): array => [
                        'ID' => $product->id,
                        'Name' => $product->name,
                        'Price (KES)' => number_format((float) $product->price, 2),
                        'Stock' => $product->stock,
                        'Active' => $product->is_active ? 'Yes' : 'No',
                        'Created' => $product->created_at?->format('Y-m-d'),
                    ])
                    ->appends($request->query()),
            ],
            'orders' => [
                'headers' => ['ID', 'Order No', 'Customer', 'County', 'Amount (KES)', 'Status'],
                'rows' => Order::query()
                    ->latest('id')
                    ->paginate(12)
                    ->through(fn (Order $order): array => [
                        'ID' => $order->id,
                        'Order No' => $order->order_number,
                        'Customer' => $order->customer_name,
                        'County' => $order->county ?? '-',
                        'Amount (KES)' => number_format((float) $order->amount, 2),
                        'Status' => ucfirst($order->status),
                    ])
                    ->appends($request->query()),
            ],
            'invoices' => [
                'headers' => ['ID', 'Invoice No', 'Order ID', 'Amount (KES)', 'Status', 'Issued'],
                'rows' => Invoice::query()
                    ->latest('id')
                    ->paginate(12)
                    ->through(fn (Invoice $invoice): array => [
                        'ID' => $invoice->id,
                        'Invoice No' => $invoice->invoice_number,
                        'Order ID' => $invoice->order_id ?? '-',
                        'Amount (KES)' => number_format((float) $invoice->amount, 2),
                        'Status' => ucfirst($invoice->status),
                        'Issued' => $invoice->issued_at?->format('Y-m-d'),
                    ])
                    ->appends($request->query()),
            ],
            'users' => [
                'headers' => ['ID', 'Name', 'Email', 'Last Login', 'Created'],
                'rows' => User::query()
                    ->latest('id')
                    ->paginate(12)
                    ->through(fn (User $user): array => [
                        'ID' => $user->id,
                        'Name' => $user->name,
                        'Email' => $user->email,
                        'Last Login' => $user->last_login_at?->format('Y-m-d H:i') ?? '-',
                        'Created' => $user->created_at?->format('Y-m-d'),
                    ])
                    ->appends($request->query()),
            ],
            'enquiries' => [
                'headers' => ['ID', 'Name', 'Email', 'Phone', 'Status', 'Created'],
                'rows' => Enquiry::query()
                    ->latest('id')
                    ->paginate(12)
                    ->through(fn (Enquiry $enquiry): array => [
                        'ID' => $enquiry->id,
                        'Name' => $enquiry->name,
                        'Email' => $enquiry->email,
                        'Phone' => $enquiry->phone ?? '-',
                        'Status' => ucfirst($enquiry->status),
                        'Created' => $enquiry->created_at?->format('Y-m-d'),
                    ])
                    ->appends($request->query()),
            ],
            default => null,
        };
    }

    private function homepageContentRecord(): HomepageContent
    {
        return HomepageContent::query()->firstOrCreate(
            ['id' => 1],
            [
                'hero_header_title' => 'Starlink Kenya | High-Speed Satellite Internet Across Kenya',
                'hero_header_description' => 'Starlink Kenya offers high-speed satellite internet with affordable packages, hardware, and monthly plans.',
                'why_choose_title' => 'Why Starlink Kenya Is Ideal for You',
                'why_choose_description' => 'Tailored for the Kenyan market.',
                'youtube_video_url' => HomepageContent::defaultYoutubeVideoUrl(),
                'products_section_title' => 'Hot-Selling Products.',
                'home_page_content' => '<h2>Starlink Kenya: A Comprehensive Guide to Satellite Internet Connectivity</h2><p>Explore the complete guide to STARLINK KENYA.</p>',
                'navigation_menu' => HomepageContent::defaultNavigationMenu(),
            ]
        );
    }
}

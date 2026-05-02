<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($homeUpdatedAt)->toAtomString() }}</lastmod>
    </url>
    @foreach ($products as $product)
        <url>
            <loc>{{ route('shop.product.show', ['productSlug' => $product->slug ?: $product->id]) }}</loc>
            <lastmod>{{ optional($product->updated_at)->toAtomString() }}</lastmod>
        </url>
    @endforeach
    @foreach ($pages as $page)
        <url>
            <loc>{{ route('site-pages.show', ['page' => $page->slug]) }}</loc>
            <lastmod>{{ optional($page->updated_at)->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>

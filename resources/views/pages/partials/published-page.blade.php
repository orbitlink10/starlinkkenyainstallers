@php
    use Illuminate\Support\Str;

    $pageTitle = trim((string) $page->page_title);
    $contentHtml = trim((string) $page->page_description);
    $heroImage = $page->image_path
        ? route('media.show', ['path' => $page->image_path])
        : null;
    $showPreviewBadge = $showPreviewBadge ?? false;
    $imageAlt = $page->image_alt_text ?: $pageTitle;
    $pageDate = $page->created_at?->format('M d, Y') ?? now()->format('M d, Y');
    $shopUrl = route('home').'#packages';
    $expertUrl = 'tel:+254700123456';

    $normalizedPageTitle = (string) Str::of(html_entity_decode(strip_tags($pageTitle), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
        ->squish()
        ->lower();

    if ($contentHtml !== '') {
        $contentHtml = preg_replace_callback(
            '/^\s*<(h[1-3])\b[^>]*>(.*?)<\/\1>/isu',
            static function (array $matches) use ($normalizedPageTitle): string {
                $headingText = (string) Str::of(html_entity_decode(strip_tags($matches[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
                    ->squish()
                    ->lower();

                if ($headingText === '' || $normalizedPageTitle === '') {
                    return $matches[0];
                }

                return str_starts_with($headingText, $normalizedPageTitle) ? '' : $matches[0];
            },
            $contentHtml,
            1
        ) ?? $contentHtml;
    }

    $plainCopy = trim(preg_replace(
        '/\s+/u',
        ' ',
        strip_tags(html_entity_decode($contentHtml, ENT_QUOTES | ENT_HTML5, 'UTF-8'))
    ) ?? '');

    $pageExcerpt = trim((string) $page->meta_description);

    if ($pageExcerpt === '') {
        $pageExcerpt = Str::limit($plainCopy, 180);
    }

    if ($pageExcerpt === '') {
        $pageExcerpt = 'Explore Starlink Kenya insights, troubleshooting steps, and expert support for getting back online quickly.';
    }
@endphp

<style>
    .site-page-view {
        min-height: 100vh;
        padding: 0 0 80px;
        background:
            radial-gradient(circle at top left, rgba(255, 192, 131, 0.16) 0%, transparent 24%),
            radial-gradient(circle at top right, rgba(173, 214, 255, 0.18) 0%, transparent 28%),
            linear-gradient(180deg, #f7faff 0%, #eef4fb 100%);
    }

    .site-page-container {
        width: min(1480px, 94vw);
        margin: 24px auto 0;
    }

    .site-page-preview {
        margin: 0 0 18px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border-radius: 999px;
        border: 1px solid #c9dcf4;
        background: #edf5ff;
        padding: 11px 18px;
        color: #2f69c7;
        font-size: 14px;
        font-weight: 800;
    }

    .site-page-hero-shell {
        position: relative;
        overflow: hidden;
        border: 1px solid #dde7f2;
        border-radius: 40px;
        background:
            radial-gradient(circle at top left, rgba(255, 189, 132, 0.18) 0%, rgba(255, 189, 132, 0) 24%),
            linear-gradient(125deg, rgba(255, 255, 255, 0.98) 0%, rgba(240, 246, 255, 0.96) 100%);
        padding: 54px 54px 44px;
        box-shadow: 0 26px 72px rgba(15, 37, 79, 0.08);
    }

    .site-page-hero-shell::before,
    .site-page-hero-shell::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .site-page-hero-shell::before {
        width: 180px;
        height: 180px;
        right: -56px;
        top: -72px;
        background: radial-gradient(circle, rgba(140, 210, 255, 0.34) 0%, rgba(140, 210, 255, 0) 72%);
    }

    .site-page-hero-shell::after {
        width: 220px;
        height: 220px;
        left: -88px;
        bottom: -96px;
        background: radial-gradient(circle, rgba(255, 174, 94, 0.18) 0%, rgba(255, 174, 94, 0) 72%);
    }

    .site-page-hero {
        display: flex;
        gap: 44px;
        align-items: stretch;
    }

    .site-page-copy-side {
        flex: 1 1 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .site-page-meta {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        color: #506987;
        font-size: clamp(18px, 1.5vw, 20px);
        font-weight: 500;
    }

    .site-page-meta i {
        color: #29486f;
        font-size: 18px;
    }

    .site-page-title {
        margin: 26px 0 0;
        max-width: 9ch;
        color: #121f3d;
        font-size: clamp(48px, 6vw, 78px);
        line-height: 0.98;
        letter-spacing: -0.055em;
        font-weight: 800;
    }

    .site-page-summary {
        margin: 28px 0 0;
        max-width: 24ch;
        color: #647b99;
        font-size: clamp(20px, 1.85vw, 28px);
        line-height: 1.48;
    }

    .site-page-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin-top: 34px;
    }

    .site-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 188px;
        padding: 20px 30px;
        border-radius: 18px;
        border: 1px solid transparent;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.1;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
    }

    .site-page-btn:hover {
        transform: translateY(-2px);
    }

    .site-page-btn--solid {
        background: #22272e;
        color: #fff;
        box-shadow: 0 16px 30px rgba(34, 39, 46, 0.14);
    }

    .site-page-btn--ghost {
        border-color: #2a2f36;
        background: rgba(255, 255, 255, 0.68);
        color: #182136;
    }

    .site-page-visual {
        position: relative;
        flex: 0 0 min(48%, 720px);
        min-height: 520px;
        overflow: hidden;
        border-radius: 30px;
        border: 1px solid #dbe6f5;
        background:
            radial-gradient(circle at top right, rgba(255, 176, 88, 0.2) 0%, transparent 32%),
            linear-gradient(145deg, rgba(223, 234, 250, 0.92) 0%, rgba(240, 245, 255, 0.98) 100%);
        box-shadow: 0 22px 42px rgba(15, 37, 79, 0.1);
    }

    .site-page-visual::before,
    .site-page-visual::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        pointer-events: none;
    }

    .site-page-visual::before {
        inset: 22px;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.78);
    }

    .site-page-visual::after {
        width: 180px;
        height: 180px;
        left: -40px;
        bottom: -56px;
        background: radial-gradient(circle, rgba(255, 186, 110, 0.22) 0%, rgba(255, 186, 110, 0) 72%);
    }

    .site-page-visual.has-image {
        background: #eaf2ff;
    }

    .site-page-visual img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .site-page-visual-placeholder {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        padding: 42px;
        background:
            linear-gradient(160deg, rgba(255, 255, 255, 0.45) 0%, rgba(217, 231, 251, 0.22) 100%);
    }

    .site-page-visual-placeholder::before {
        content: '';
        width: min(78%, 420px);
        aspect-ratio: 1;
        border-radius: 32px;
        background:
            radial-gradient(circle at 24% 22%, rgba(255, 167, 66, 0.28) 0%, transparent 28%),
            radial-gradient(circle at 72% 70%, rgba(130, 214, 255, 0.32) 0%, transparent 34%),
            linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(232, 241, 255, 0.92) 100%);
        box-shadow:
            inset 0 0 0 1px rgba(255, 255, 255, 0.85),
            0 24px 54px rgba(26, 53, 94, 0.12);
    }

    .site-page-body {
        margin-top: 28px;
        border: 1px solid #dde7f2;
        border-radius: 34px;
        background: rgba(255, 255, 255, 0.96);
        padding: 42px 46px 52px;
        box-shadow: 0 22px 56px rgba(15, 37, 79, 0.06);
    }

    .site-page-copy {
        max-width: 960px;
        color: #3a557d;
    }

    .site-page-copy > *:first-child {
        margin-top: 0;
    }

    .site-page-copy > *:last-child {
        margin-bottom: 0;
    }

    .site-page-copy p,
    .site-page-copy ul,
    .site-page-copy ol {
        margin: 0;
        font-size: clamp(18px, 1.3vw, 21px);
        line-height: 1.82;
    }

    .site-page-copy h1,
    .site-page-copy h2,
    .site-page-copy h3 {
        position: relative;
        margin: 50px 0 0;
        padding-bottom: 18px;
        color: #0a224d;
        letter-spacing: -0.04em;
        line-height: 1.12;
    }

    .site-page-copy h1 {
        font-size: clamp(34px, 3vw, 48px);
    }

    .site-page-copy h2 {
        font-size: clamp(31px, 2.55vw, 42px);
    }

    .site-page-copy h3 {
        font-size: clamp(26px, 2.1vw, 34px);
    }

    .site-page-copy h1::after,
    .site-page-copy h2::after,
    .site-page-copy h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 72px;
        height: 4px;
        border-radius: 999px;
        background: linear-gradient(90deg, #f28c1f 0 56%, #7fd6f3 56% 100%);
    }

    .site-page-copy ul,
    .site-page-copy ol {
        padding-left: 28px;
    }

    .site-page-copy li + li {
        margin-top: 10px;
    }

    .site-page-copy blockquote {
        margin: 34px 0 0;
        border-left: 4px solid #ff961f;
        padding: 6px 0 6px 20px;
        color: #27425f;
        font-size: clamp(20px, 1.65vw, 24px);
        font-weight: 600;
        line-height: 1.7;
    }

    .site-page-copy img {
        display: block;
        width: 100%;
        max-width: 980px;
        margin-top: 34px;
        border-radius: 26px;
        height: auto;
    }

    .site-page-copy table {
        width: 100%;
        margin-top: 34px;
        border-collapse: collapse;
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid #e2eaf4;
        background: #fff;
    }

    .site-page-copy th,
    .site-page-copy td {
        border: 1px solid #e2eaf4;
        padding: 14px 16px;
        text-align: left;
        font-size: 16px;
    }

    .site-page-copy th {
        background: #f7faff;
        color: #133764;
        font-weight: 800;
    }

    .site-page-copy hr {
        margin: 36px 0 0;
        border: 0;
        border-top: 1px solid #e4ebf4;
    }

    .site-page-copy * + p,
    .site-page-copy * + ul,
    .site-page-copy * + ol,
    .site-page-copy * + blockquote,
    .site-page-copy * + table,
    .site-page-copy * + hr {
        margin-top: 28px;
    }

    @media (max-width: 1180px) {
        .site-page-hero {
            flex-direction: column;
        }

        .site-page-title,
        .site-page-summary,
        .site-page-copy {
            max-width: none;
        }

        .site-page-visual {
            flex-basis: auto;
            min-height: 420px;
        }
    }

    @media (max-width: 900px) {
        .site-page-view {
            padding: 0 0 56px;
        }

        .site-page-container {
            margin-top: 18px;
        }

        .site-page-hero-shell,
        .site-page-body {
            padding: 28px 24px 32px;
            border-radius: 30px;
        }

        .site-page-title {
            margin-top: 20px;
            font-size: clamp(38px, 10vw, 52px);
        }

        .site-page-summary {
            margin-top: 22px;
            font-size: 18px;
        }

        .site-page-actions {
            margin-top: 26px;
        }

        .site-page-btn {
            width: 100%;
            min-width: 0;
            padding: 18px 22px;
        }

        .site-page-visual {
            min-height: 280px;
            border-radius: 24px;
        }

        .site-page-copy p,
        .site-page-copy ul,
        .site-page-copy ol {
            font-size: 16px;
            line-height: 1.78;
        }

        .site-page-copy h1,
        .site-page-copy h2,
        .site-page-copy h3 {
            margin-top: 38px;
            padding-bottom: 16px;
        }

        .site-page-copy h1 {
            font-size: clamp(28px, 7vw, 38px);
        }

        .site-page-copy h2 {
            font-size: clamp(26px, 6.4vw, 34px);
        }

        .site-page-copy h3 {
            font-size: clamp(22px, 5.8vw, 30px);
        }

        .site-page-copy blockquote {
            font-size: 18px;
        }
    }
</style>

<main class="site-page-view">
    @include('shared.storefront-header')

    <div class="site-page-container">
        @include('shared.quick-links-nav', ['variant' => 'inline', 'navLabel' => 'Page navigation'])

        @if ($showPreviewBadge)
            <div class="site-page-preview">
                <i class="fa-solid fa-eye"></i>
                <span>Page Preview</span>
            </div>
        @endif

        <section class="site-page-hero-shell">
            <div class="site-page-hero">
                <div class="site-page-copy-side">
                    <p class="site-page-meta">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $pageDate }}</span>
                    </p>

                    <h1 class="site-page-title">{{ $pageTitle }}</h1>

                    <p class="site-page-summary">{{ $pageExcerpt }}</p>

                    <div class="site-page-actions">
                        <a class="site-page-btn site-page-btn--solid" href="{{ $shopUrl }}">Shop Now</a>
                        <a class="site-page-btn site-page-btn--ghost" href="{{ $expertUrl }}">Talk to an Expert</a>
                    </div>
                </div>

                <div class="site-page-visual {{ $heroImage ? 'has-image' : '' }}" aria-hidden="true">
                    @if ($heroImage)
                        <img src="{{ $heroImage }}" alt="{{ $imageAlt }}">
                    @else
                        <div class="site-page-visual-placeholder"></div>
                    @endif
                </div>
            </div>
        </section>

        <section class="site-page-body">
            @if ($contentHtml !== '')
                <article class="site-page-copy">
                    {!! $contentHtml !!}
                </article>
            @else
                <div class="site-page-copy">
                    <p>Content for this page will appear here once it has been added in the page editor.</p>
                </div>
            @endif
        </section>
    </div>
</main>

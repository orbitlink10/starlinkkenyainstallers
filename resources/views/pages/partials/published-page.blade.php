@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    $pageTitle = trim((string) $page->page_title);
    $contentHtml = trim((string) $page->page_description);
    $heroImagePath = trim((string) $page->image_path, '/');
    $normalizedHeroImagePath = preg_replace('#^public/#', '', $heroImagePath) ?? $heroImagePath;
    $heroImage = null;

    if ($normalizedHeroImagePath !== '') {
        $publicDisk = Storage::disk('public');
        $heroImage = $publicDisk->exists($normalizedHeroImagePath)
            ? url('storage/'.$normalizedHeroImagePath)
            : route('media.show', ['path' => $normalizedHeroImagePath]);
    }
    $showPreviewBadge = $showPreviewBadge ?? false;
    $imageAlt = $page->image_alt_text ?: $pageTitle;
    $pageDate = $page->created_at?->format('M d, Y') ?? now()->format('M d, Y');
    $shopUrl = route('home').'#packages';
    $expertUrl = 'tel:+254700123456';
    $backUrl = $backUrl ?? route('home');
    $backLabel = $backLabel ?? 'Back';
    $pageLabel = trim((string) $page->heading_2);

    $normalizedPageTitle = (string) Str::of(html_entity_decode(strip_tags($pageTitle), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
        ->squish()
        ->lower();

    if ($pageLabel === '') {
        $pageLabel = trim((string) Str::of($pageTitle)->before(':'));
    }

    if ($pageLabel === '' || mb_strlen($pageLabel) > 42) {
        $pageLabel = trim((string) Str::of($pageTitle)->words(4, ''));
    }

    if ($pageLabel === '') {
        $pageLabel = 'Starlink Kenya';
    }

    $articleTitle = $pageTitle;

    if ($contentHtml !== '') {
        if (preg_match('/^\s*<(h[1-3])\b[^>]*>(.*?)<\/\1>/isu', $contentHtml, $matches) === 1) {
            $firstHeadingText = trim((string) preg_replace(
                '/\s+/u',
                ' ',
                strip_tags(html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            ));

            if ($firstHeadingText !== '') {
                $articleTitle = $firstHeadingText;
                $contentHtml = preg_replace('/^\s*<(h[1-3])\b[^>]*>.*?<\/\1>/isu', '', $contentHtml, 1) ?? $contentHtml;
            }
        }
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
        width: min(1500px, 94vw);
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
        padding: 34px 36px 30px;
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
        gap: 32px;
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
        font-size: clamp(15px, 1.1vw, 17px);
        font-weight: 500;
    }

    .site-page-meta i {
        color: #29486f;
        font-size: 16px;
    }

    .site-page-title {
        margin: 20px 0 0;
        max-width: 11.5ch;
        color: #121f3d;
        font-size: clamp(30px, 3.3vw, 44px);
        line-height: 1.08;
        letter-spacing: -0.055em;
        font-weight: 800;
    }

    .site-page-summary {
        margin: 22px 0 0;
        max-width: 27ch;
        color: #647b99;
        font-size: clamp(16px, 1.15vw, 19px);
        line-height: 1.58;
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
    }

    .site-page-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 28px;
    }

    .site-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 170px;
        padding: 16px 24px;
        border-radius: 16px;
        border: 1px solid transparent;
        font-size: 15px;
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
        flex: 0 0 min(45%, 660px);
        min-height: 380px;
        overflow: hidden;
        border-radius: 26px;
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
        inset: 18px;
        border-radius: 20px;
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
        z-index: 1;
    }

    .site-page-visual-placeholder {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        padding: 34px;
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
        position: relative;
        max-width: 1000px;
        margin: 30px auto 0;
        border: 1px solid #dde7f2;
        border-radius: 36px;
        background: rgba(255, 255, 255, 0.96);
        padding: 34px 38px 40px 46px;
        box-shadow: 0 22px 56px rgba(15, 37, 79, 0.06);
        overflow: hidden;
    }

    .site-page-body::before {
        content: '';
        position: absolute;
        left: 0;
        top: 18px;
        bottom: 18px;
        width: 12px;
        border-radius: 0 999px 999px 0;
        background: linear-gradient(180deg, #ff8615 0%, #ffb452 100%);
    }

    .site-page-body::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        pointer-events: none;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .site-page-article-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .site-page-article-kicker {
        margin: 0;
        color: #0b4b8e;
        font-size: clamp(18px, 1.45vw, 24px);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .site-page-article-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        border: 1px solid #d8e2ef;
        background: rgba(255, 255, 255, 0.92);
        padding: 12px 18px;
        color: #ff7c11;
        font-size: 15px;
        font-weight: 700;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        box-shadow: 0 10px 24px rgba(15, 37, 79, 0.04);
        white-space: nowrap;
    }

    .site-page-article-back:hover {
        transform: translateY(-1px);
        border-color: #ffcf9c;
        box-shadow: 0 14px 28px rgba(15, 37, 79, 0.08);
    }

    .site-page-article-title {
        margin: 0;
        max-width: 18ch;
        color: #131d39;
        font-size: clamp(30px, 2.9vw, 40px);
        line-height: 1.04;
        letter-spacing: -0.055em;
        font-weight: 500;
    }

    .site-page-article-image {
        display: block;
        width: min(100%, 900px);
        margin-top: 18px;
        border-radius: 24px;
        border: 1px solid #e2eaf4;
        box-shadow: 0 18px 42px rgba(15, 37, 79, 0.08);
        height: auto;
    }

    .site-page-copy {
        margin-top: 20px;
        max-width: 900px;
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
        font-size: clamp(16px, 0.98vw, 18px);
        line-height: 1.62;
        letter-spacing: -0.01em;
    }

    .site-page-copy h1,
    .site-page-copy h2,
    .site-page-copy h3 {
        position: relative;
        margin: 26px 0 0;
        padding-bottom: 10px;
        color: #0a224d;
        letter-spacing: -0.04em;
        line-height: 1.14;
    }

    .site-page-copy h1 {
        font-size: clamp(24px, 1.7vw, 30px);
    }

    .site-page-copy h2 {
        font-size: clamp(22px, 1.5vw, 26px);
    }

    .site-page-copy h3 {
        font-size: clamp(19px, 1.3vw, 22px);
    }

    .site-page-copy h1::after,
    .site-page-copy h2::after,
    .site-page-copy h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 54px;
        height: 3px;
        border-radius: 999px;
        background: linear-gradient(90deg, #f28c1f 0 56%, #7fd6f3 56% 100%);
    }

    .site-page-copy ul,
    .site-page-copy ol {
        padding-left: 32px;
    }

    .site-page-copy li + li {
        margin-top: 8px;
    }

    .site-page-copy blockquote {
        margin: 20px 0 0;
        border-left: 4px solid #ff961f;
        padding: 6px 0 6px 20px;
        color: #27425f;
        font-size: clamp(17px, 1.05vw, 19px);
        font-weight: 600;
        line-height: 1.58;
    }

    .site-page-copy img {
        display: block;
        width: 100%;
        max-width: 980px;
        margin-top: 20px;
        border-radius: 24px;
        height: auto;
    }

    .site-page-copy table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        overflow: hidden;
        border-radius: 18px;
        border: 1px solid #e2eaf4;
        background: #fff;
    }

    .site-page-copy th,
    .site-page-copy td {
        border: 1px solid #e2eaf4;
        padding: 14px 16px;
        text-align: left;
        font-size: 15px;
    }

    .site-page-copy th {
        background: #f7faff;
        color: #133764;
        font-weight: 800;
    }

    .site-page-copy hr {
        margin: 24px 0 0;
        border: 0;
        border-top: 1px solid #e4ebf4;
    }

    .site-page-copy p + p,
    .site-page-copy li > p + p {
        margin-top: 10px;
    }

    .site-page-copy h1 + p,
    .site-page-copy h2 + p,
    .site-page-copy h3 + p,
    .site-page-copy h1 + ul,
    .site-page-copy h2 + ul,
    .site-page-copy h3 + ul,
    .site-page-copy h1 + ol,
    .site-page-copy h2 + ol,
    .site-page-copy h3 + ol,
    .site-page-copy p + ul,
    .site-page-copy p + ol,
    .site-page-copy ul + p,
    .site-page-copy ol + p,
    .site-page-copy ul + ul,
    .site-page-copy ol + ol,
    .site-page-copy ul + ol,
    .site-page-copy ol + ul,
    .site-page-copy * + blockquote,
    .site-page-copy * + table,
    .site-page-copy * + hr {
        margin-top: 14px;
    }

    @media (max-width: 1180px) {
        .site-page-hero {
            flex-direction: column;
        }

        .site-page-title,
        .site-page-summary {
            max-width: none;
        }

        .site-page-visual {
            flex-basis: auto;
            min-height: 320px;
        }

        .site-page-body,
        .site-page-copy,
        .site-page-article-title {
            max-width: none;
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
            padding: 24px 20px 28px;
            border-radius: 24px;
        }

        .site-page-title {
            margin-top: 16px;
            font-size: clamp(28px, 7vw, 34px);
        }

        .site-page-summary {
            margin-top: 18px;
            font-size: 15px;
            -webkit-line-clamp: 3;
        }

        .site-page-actions {
            margin-top: 22px;
        }

        .site-page-btn {
            width: 100%;
            min-width: 0;
            padding: 16px 20px;
        }

        .site-page-visual {
            min-height: 220px;
            border-radius: 20px;
        }

        .site-page-copy p,
        .site-page-copy ul,
        .site-page-copy ol {
            font-size: 15px;
            line-height: 1.58;
        }

        .site-page-copy h1,
        .site-page-copy h2,
        .site-page-copy h3 {
            margin-top: 22px;
            padding-bottom: 10px;
        }

        .site-page-copy h1 {
            font-size: clamp(21px, 5.7vw, 25px);
        }

        .site-page-copy h2 {
            font-size: clamp(19px, 5vw, 23px);
        }

        .site-page-copy h3 {
            font-size: clamp(17px, 4.4vw, 20px);
        }

        .site-page-copy blockquote {
            font-size: 16px;
        }

        .site-page-body {
            margin-top: 26px;
            padding: 24px 16px 28px 22px;
            border-radius: 26px;
        }

        .site-page-body::before {
            top: 18px;
            bottom: 18px;
            width: 8px;
        }

        .site-page-article-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 14px;
        }

        .site-page-article-kicker {
            font-size: 16px;
        }

        .site-page-article-back {
            padding: 10px 15px;
            font-size: 14px;
        }

        .site-page-article-title {
            max-width: none;
            font-size: clamp(24px, 6.6vw, 30px);
            line-height: 1.05;
        }

        .site-page-article-image {
            margin-top: 16px;
            border-radius: 20px;
        }

        .site-page-copy {
            margin-top: 18px;
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
                    <div class="site-page-visual-placeholder"></div>

                    @if ($heroImage)
                        <img src="{{ $heroImage }}" alt="{{ $imageAlt }}" loading="eager" decoding="async" onerror="this.style.display='none'; this.closest('.site-page-visual').classList.remove('has-image');">
                    @endif
                </div>
            </div>
        </section>

        <section class="site-page-body">
            <div class="site-page-article-header">
                <p class="site-page-article-kicker">{{ $pageLabel }}</p>
                <a class="site-page-article-back" href="{{ $backUrl }}">{{ $backLabel }}</a>
            </div>

            <h2 class="site-page-article-title">{{ $articleTitle }}</h2>

            @if ($heroImage)
                <img class="site-page-article-image" src="{{ $heroImage }}" alt="{{ $imageAlt }}" loading="eager" decoding="async" onerror="this.style.display='none';">
            @endif

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

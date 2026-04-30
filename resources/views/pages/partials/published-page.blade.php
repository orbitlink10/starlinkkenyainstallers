@php
    $pageTitle = trim((string) $page->page_title);
    $pageLabel = trim((string) $page->heading_2);

    if ($pageLabel === '') {
        $pageLabel = trim((string) \Illuminate\Support\Str::of($pageTitle)->before(':'));
    }

    if ($pageLabel === '' || mb_strlen($pageLabel) > 42) {
        $pageLabel = trim((string) \Illuminate\Support\Str::of($pageTitle)->words(4, ''));
    }

    if ($pageLabel === '') {
        $pageLabel = 'Starlink Kenya';
    }

    $contentHtml = trim((string) $page->page_description);
    $heroImage = $page->image_path
        ? route('media.show', ['path' => $page->image_path])
        : 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1200&q=80';
    $backUrl = $backUrl ?? route('home');
    $backLabel = $backLabel ?? 'Back';
    $showPreviewBadge = $showPreviewBadge ?? false;
    $imageAlt = $page->image_alt_text ?: $pageTitle;
@endphp

<style>
    .site-page-view {
        min-height: 100vh;
        padding: 46px 0 74px;
    }

    .site-page-container {
        width: min(1400px, 92vw);
        margin: 0 auto;
    }

    .site-page-preview {
        margin-bottom: 18px;
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

    .site-page-frame {
        position: relative;
        overflow: hidden;
        border: 1px solid #dde7f2;
        border-radius: 40px;
        background:
            radial-gradient(circle at top right, rgba(255, 186, 110, 0.12) 0%, rgba(255, 186, 110, 0) 20%),
            linear-gradient(180deg, rgba(255, 255, 255, 0.99) 0%, rgba(250, 252, 255, 0.99) 100%);
        padding: 56px 56px 64px;
        box-shadow: 0 30px 74px rgba(15, 37, 79, 0.08);
    }

    .site-page-frame::before {
        content: '';
        position: absolute;
        left: 0;
        top: 44px;
        bottom: 44px;
        width: 12px;
        border-radius: 0 999px 999px 0;
        background: linear-gradient(180deg, #ff8615 0%, #ffb452 100%);
    }

    .site-page-frame::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        pointer-events: none;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.92);
    }

    .site-page-topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
    }

    .site-page-kicker {
        margin: 0;
        color: #0b4b8e;
        font-size: clamp(20px, 1.7vw, 26px);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .site-page-back {
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
    }

    .site-page-back:hover {
        transform: translateY(-1px);
        border-color: #ffcf9c;
        box-shadow: 0 14px 28px rgba(15, 37, 79, 0.08);
    }

    .site-page-title {
        margin: 24px 0 0;
        max-width: 1120px;
        color: #081d47;
        font-size: clamp(42px, 4.2vw, 64px);
        line-height: 1.08;
        letter-spacing: -0.055em;
        font-weight: 500;
    }

    .site-page-hero {
        display: block;
        margin-top: 26px;
        width: 100%;
        max-width: 930px;
        border-radius: 30px;
        border: 1px solid #e2eaf4;
        background: #eef6ff;
        box-shadow: 0 18px 42px rgba(15, 37, 79, 0.1);
        object-fit: cover;
        aspect-ratio: 16 / 9;
    }

    .site-page-copy {
        margin-top: 42px;
        max-width: 1240px;
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
        font-size: clamp(18px, 1.38vw, 21px);
        line-height: 1.84;
    }

    .site-page-copy h1,
    .site-page-copy h2,
    .site-page-copy h3 {
        position: relative;
        margin: 54px 0 0;
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

    @media (max-width: 900px) {
        .site-page-view {
            padding: 28px 0 56px;
        }

        .site-page-frame {
            padding: 28px 26px 34px;
            border-radius: 30px;
        }

        .site-page-frame::before {
            top: 24px;
            bottom: 24px;
            width: 8px;
        }

        .site-page-topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .site-page-back {
            padding: 11px 16px;
        }

        .site-page-title {
            margin-top: 18px;
            font-size: clamp(34px, 9vw, 48px);
        }

        .site-page-hero {
            margin-top: 22px;
            border-radius: 22px;
        }

        .site-page-copy {
            margin-top: 30px;
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
    <div class="site-page-container">
        @include('shared.quick-links-nav')

    @if ($showPreviewBadge)
        <div class="site-page-preview">
            <i class="fa-solid fa-eye"></i>
            <span>Page Preview</span>
        </div>
    @endif

        <section class="site-page-frame">
            <div class="site-page-topbar">
                <p class="site-page-kicker">{{ $pageLabel }}</p>
                <a class="site-page-back" href="{{ $backUrl }}">{{ $backLabel }}</a>
            </div>

            <h1 class="site-page-title">{{ $pageTitle }}</h1>

            @if ($heroImage)
                <img
                    class="site-page-hero"
                    src="{{ $heroImage }}"
                    alt="{{ $imageAlt }}"
                >
            @endif

            @if ($contentHtml !== '')
                <div class="site-page-copy">
                    {!! $contentHtml !!}
                </div>
            @else
                <div class="site-page-copy">
                    <p>Content for this page will appear here once it has been added in the page editor.</p>
                </div>
            @endif
        </section>
    </div>
</main>

@extends('layouts.app', ['title' => $page->meta_title ?: $page->page_title.' | Preview'])

@section('content')
    <style>
        .preview-wrap {
            background: #eef2f7;
            min-height: 100vh;
            padding: 28px 0 42px;
        }

        .preview-container {
            width: min(980px, 94vw);
            margin: 0 auto;
        }

        .preview-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .preview-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #355073;
            background: #deebff;
            border: 1px solid #bdd4f7;
            border-radius: 999px;
            padding: 8px 14px;
        }

        .preview-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #2158b4;
            font-size: 14px;
            font-weight: 700;
            background: #fff;
            border: 1px solid #c8d6ec;
            border-radius: 999px;
            padding: 9px 14px;
        }

        .preview-card {
            border-radius: 18px;
            border: 1px solid #d8dfeb;
            background: #fff;
            overflow: hidden;
        }

        .preview-image {
            width: 100%;
            height: min(420px, 48vw);
            object-fit: cover;
            display: block;
            border-bottom: 1px solid #e4eaf3;
        }

        .preview-body {
            padding: 24px;
        }

        .preview-title {
            margin: 0;
            color: #102645;
            font-size: clamp(28px, 4vw, 44px);
            line-height: 1.15;
            letter-spacing: -.02em;
        }

        .preview-subtitle {
            margin: 14px 0 0;
            color: #324c71;
            font-size: clamp(20px, 2.5vw, 30px);
            line-height: 1.2;
        }

        .preview-content {
            margin-top: 18px;
            color: #354f73;
            line-height: 1.68;
            font-size: 18px;
        }

        .preview-content h2,
        .preview-content h3 {
            color: #123a74;
            line-height: 1.25;
            margin: 0 0 12px;
        }

        .preview-content p {
            margin: 0 0 14px;
        }

        .preview-content ul,
        .preview-content ol {
            margin: 0 0 14px 22px;
        }
    </style>

    <main class="preview-wrap">
        <div class="preview-container">
            <div class="preview-top">
                <span class="preview-tag"><i class="fa-solid fa-eye"></i> Page Preview</span>
                <a class="preview-back" href="{{ route('pages.index') }}"><i class="fa-solid fa-arrow-left"></i> Back to posts</a>
            </div>

            <article class="preview-card">
                @if ($page->image_path)
                    <img class="preview-image" src="{{ asset('storage/'.$page->image_path) }}" alt="{{ $page->image_alt_text ?: $page->page_title }}">
                @endif

                <div class="preview-body">
                    <h1 class="preview-title">{{ $page->page_title }}</h1>

                    @if ($page->heading_2)
                        <h2 class="preview-subtitle">{{ $page->heading_2 }}</h2>
                    @endif

                    @if ($page->page_description)
                        <div class="preview-content">
                            {!! $page->page_description !!}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </main>
@endsection

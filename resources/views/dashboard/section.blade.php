@extends('layouts.app', ['title' => $title.' | Starlink Kenya Installers'])

@section('content')
    <style>
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 340px;
            background: #eef1f6;
            border-right: 1px solid #dbe1eb;
            padding: 16px 0 24px;
        }

        .brand {
            display: block;
            margin: 0 18px 20px;
            background: #f8fafd;
            border: 1px solid #d8dfec;
            border-radius: 24px;
            padding: 18px 34px;
            font-size: 22px;
            line-height: 1.1;
            font-weight: 800;
            color: #353f4e;
            letter-spacing: -0.8px;
            text-decoration: none;
        }

        .menu-block {
            margin-bottom: 18px;
        }

        .menu-title {
            margin: 8px 24px 8px;
            font-size: 13px;
            font-weight: 600;
            color: #8b9ab1;
            letter-spacing: .24em;
            text-transform: uppercase;
        }

        .menu-link,
        .menu-logout {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 6px 0;
            padding: 16px 22px;
            border-radius: 0 14px 14px 0;
            font-size: 17px;
            line-height: 1.15;
            font-weight: 700;
            color: #46556f;
        }

        .menu-link.active {
            background: linear-gradient(90deg, #3b74f3, #2f66f3);
            color: #fff;
            box-shadow: 0 12px 24px rgba(47, 102, 243, .22);
        }

        .menu-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: #dce3ee;
            color: #62738f;
            font-size: 18px;
            flex-shrink: 0;
        }

        .menu-link.active .menu-icon {
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        .menu-logout {
            width: 100%;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .main {
            flex: 1;
            padding: 24px 28px;
        }

        .content-card {
            border-radius: 18px;
            background: #fff;
            border: 1px solid #dce3ef;
            padding: 24px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 8px 16px;
            letter-spacing: .15em;
            color: #3f587f;
            background: #e6edf8;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
        }

        .page-title {
            margin: 12px 0 0;
            font-size: 40px;
            letter-spacing: -1px;
            color: #1f2a3a;
        }

        .subtitle {
            margin: 8px 0 0;
            color: #586c89;
            font-size: 19px;
            font-weight: 500;
        }

        .head-actions {
            margin-top: 16px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-btn {
            border: 1px solid #8d99ae;
            color: #4f607a;
            background: #f3f6fc;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 700;
        }

        .table-wrap {
            margin-top: 22px;
            overflow-x: auto;
            border: 1px solid #e1e7f1;
            border-radius: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            min-width: 760px;
        }

        .table th,
        .table td {
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid #ebf0f7;
            color: #3a4d6b;
            font-size: 14px;
            white-space: nowrap;
        }

        .table th {
            background: #f5f8fd;
            color: #314562;
            font-weight: 800;
        }

        .empty-note {
            margin-top: 16px;
            color: #607491;
            font-size: 15px;
            line-height: 1.55;
        }

        .pager {
            margin-top: 16px;
        }

        .pager nav {
            display: flex;
            justify-content: center;
        }

        .pager svg {
            width: 14px;
            height: 14px;
        }

        .flash-success {
            margin-top: 14px;
            border: 1px solid #bfe5cd;
            background: #ecfff2;
            color: #1f6c3d;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .flash-error {
            margin-top: 14px;
            border: 1px solid #f4b4bd;
            background: #fff1f3;
            color: #9f2536;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .form-grid {
            margin-top: 18px;
            display: grid;
            gap: 16px;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #223552;
            font-size: 15px;
            font-weight: 800;
        }

        .field-help {
            margin: 6px 0 10px;
            color: #5a6f8e;
            font-size: 13px;
            line-height: 1.5;
        }

        .field-input,
        .field-textarea {
            width: 100%;
            border: 1px solid #d0d8e7;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
            color: #2f4564;
            font-family: inherit;
            background: #fff;
        }

        .field-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .file-input {
            width: 100%;
            border: 1px solid #d0d8e7;
            border-radius: 12px;
            padding: 8px;
            background: #fff;
        }

        .hero-preview {
            margin-top: 10px;
            border: 1px solid #d7dfec;
            border-radius: 14px;
            max-width: 640px;
            overflow: hidden;
            background: #f7f9fc;
        }

        .hero-preview img {
            display: block;
            width: 100%;
            height: auto;
        }

        .save-btn {
            justify-self: start;
            border: 0;
            background: #1f6ff2;
            color: #fff;
            border-radius: 12px;
            padding: 11px 18px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
        }

        @media (max-width: 1200px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #dbe1eb;
            }
        }
    </style>

    <div class="layout">
        @include('dashboard.partials.sidebar')

        <main class="main">
            <section class="content-card">
                <span class="chip">Admin Section</span>
                <h1 class="page-title">{{ $title }}</h1>
                <p class="subtitle">{{ $description }}</p>

                <div class="head-actions">
                    <a class="action-btn" href="{{ route('dashboard') }}">Back to Dashboard</a>
                    <a class="action-btn" href="{{ route('admin.section', ['section' => $section]) }}">Refresh</a>
                </div>

                @if ($section === 'homepage-content' && isset($homepageContent))
                    @if (session('success'))
                        <div class="flash-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="flash-error">{{ $errors->first() }}</div>
                    @endif

                    <form class="form-grid" method="POST" action="{{ route('admin.homepage-content.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label class="field-label" for="hero_header_title">Hero Header Title</label>
                            <input class="field-input" id="hero_header_title" name="hero_header_title" type="text" value="{{ old('hero_header_title', $homepageContent->hero_header_title) }}" required>
                        </div>

                        <div>
                            <label class="field-label" for="hero_header_description">Hero Header Description</label>
                            <textarea class="field-textarea" id="hero_header_description" name="hero_header_description">{{ old('hero_header_description', $homepageContent->hero_header_description) }}</textarea>
                        </div>

                        <div>
                            <label class="field-label" for="hero_image">Hero Image (1280 x 720)</label>
                            <input class="file-input" id="hero_image" name="hero_image" type="file" accept=".jpg,.jpeg,.png,.webp">

                            @if ($homepageContent->hero_image_path)
                                <div class="hero-preview">
                                    <img src="{{ asset('storage/'.$homepageContent->hero_image_path) }}" alt="Hero image preview">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="field-label" for="why_choose_title">Why Choose Title</label>
                            <input class="field-input" id="why_choose_title" name="why_choose_title" type="text" value="{{ old('why_choose_title', $homepageContent->why_choose_title) }}">
                        </div>

                        <div>
                            <label class="field-label" for="why_choose_description">Why Choose Description</label>
                            <textarea class="field-textarea" id="why_choose_description" name="why_choose_description">{{ old('why_choose_description', $homepageContent->why_choose_description) }}</textarea>
                        </div>

                        <div>
                            <label class="field-label" for="products_section_title">Products Section Title</label>
                            <input class="field-input" id="products_section_title" name="products_section_title" type="text" value="{{ old('products_section_title', $homepageContent->products_section_title) }}">
                        </div>

                        <div>
                            <label class="field-label" for="home_page_content">Home Page Content</label>
                            <p class="field-help">Use H2/H3 headings and paragraphs for SEO structure. You can also use bullet lists.</p>
                            <textarea class="field-textarea js-home-editor" id="home_page_content" name="home_page_content" style="min-height:260px;">{{ old('home_page_content', $homepageContent->home_page_content) }}</textarea>
                        </div>

                        <button class="save-btn" type="submit">Save Homepage Content</button>
                    </form>

                    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
                    <script>
                        if (window.tinymce) {
                            tinymce.init({
                                selector: '.js-home-editor',
                                height: 360,
                                menubar: 'file edit view insert format tools',
                                plugins: 'lists link table code',
                                toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | code',
                                block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3',
                            });
                        }
                    </script>
                @elseif ($table)
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach ($table['headers'] as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($table['rows'] as $row)
                                    <tr>
                                        @foreach ($row as $value)
                                            <td>{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($table['headers']) }}">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="pager">{{ $table['rows']->onEachSide(1)->links() }}</div>
                @else
                    <p class="empty-note">
                        This module is active and routed correctly. Data CRUD screens can be added next based on your workflow requirements.
                    </p>
                @endif
            </section>
        </main>
    </div>
@endsection


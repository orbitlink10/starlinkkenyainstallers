@extends('layouts.app', ['title' => $title ?? 'Admin'])

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

        .page-title {
            margin: 0;
            font-size: 50px;
            color: #152949;
        }

        .subtitle {
            margin: 8px 0 0;
            color: #607491;
            font-size: 18px;
        }

        .content-card {
            margin-top: 22px;
            border-radius: 18px;
            background: #fff;
            border: 1px solid #dce3ef;
            overflow: hidden;
        }

        .section-bar {
            padding: 16px 24px;
            background: #1578f0;
            color: #fff;
            font-size: 34px;
            font-weight: 800;
        }

        .toolbar {
            padding: 18px 24px;
            border-bottom: 1px solid #e3e8f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .field-input,
        .field-textarea,
        .field-select,
        .field-file {
            width: 100%;
            border: 1px solid #d0d8e7;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 16px;
            color: #2f4564;
            font-family: inherit;
            background: #fff;
        }

        .field-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .field-file {
            padding: 8px;
        }

        .btn {
            border: 0;
            background: #1f6ff2;
            color: #fff;
            border-radius: 999px;
            padding: 11px 20px;
            font-size: 15px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline {
            border: 1px solid #8da2c1;
            color: #4b5f80;
            background: #f6f9ff;
            border-radius: 999px;
            padding: 9px 16px;
            font-weight: 700;
            font-size: 14px;
        }

        .form-grid {
            padding: 20px 24px 24px;
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

        .table-wrap {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        .table th,
        .table td {
            padding: 14px 16px;
            text-align: left;
            border-top: 1px solid #e5ebf3;
            color: #2b4263;
            font-size: 15px;
            vertical-align: middle;
        }

        .table th {
            background: #f5f8fd;
            color: #4f688a;
            text-transform: uppercase;
            letter-spacing: .14em;
            font-size: 11px;
            font-weight: 800;
        }

        .thumb {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #d8dfec;
            background: #eef3fb;
        }

        .action-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid transparent;
            background: transparent;
            text-decoration: none;
            cursor: pointer;
        }

        .chip.view {
            color: #1293b8;
            border-color: #8ad6ea;
            background: #f3fdff;
        }

        .chip.edit {
            color: #d38800;
            border-color: #f3d086;
            background: #fffaf0;
        }

        .chip.delete {
            color: #e53b50;
            border-color: #f2a9b3;
            background: #fff4f6;
        }

        .flash-success {
            margin: 16px 24px 0;
            border: 1px solid #bfe5cd;
            background: #ecfff2;
            color: #1f6c3d;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .flash-error {
            margin: 16px 24px 0;
            border: 1px solid #f4b4bd;
            background: #fff1f3;
            color: #9f2536;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .pager {
            padding: 14px 24px 20px;
        }

        .pager nav {
            display: flex;
            justify-content: center;
        }

        .pager svg {
            width: 14px;
            height: 14px;
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
        @include('dashboard.partials.sidebar', ['activeSection' => $activeSection ?? 'dashboard'])

        <main class="main">
            <h1 class="page-title">{{ $heading }}</h1>
            @if (!empty($subheading))
                <p class="subtitle">{{ $subheading }}</p>
            @endif
            @yield('admin_content')
        </main>
    </div>
@endsection

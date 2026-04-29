@extends('layouts.app', ['title' => 'Starlink Kenya Installers'])

@section('content')
    @php
        $heroTitle = $homepageContent?->hero_header_title ?: 'Starlink Kenya | Official Starlink Reseller and Installer in Kenya';
        $heroDescription = $homepageContent?->hero_header_description ?: 'Starlink Kenya: Get expert Starlink installation services designed to deliver the strongest signal and maximum performance, even in remote and hard-to-reach areas.';
        $whyChooseTitle = $homepageContent?->why_choose_title ?: 'Why Starlink Kenya Is Ideal for You';
        $whyChooseDescription = $homepageContent?->why_choose_description ?: 'Tailored for the Kenyan market with expert installation and dependable support.';
        $productsTitle = $homepageContent?->products_section_title ?: 'Starlink Kits in Kenya';
        $homePageContentHtml = $homePageContentHtml ?? '<h2>Starlink Kenya: A Comprehensive Guide to Satellite Internet Connectivity</h2><p>Explore STARLINK KENYA, the satellite internet service transforming digital access across Kenya.</p>';
        $heroImageUrl = $homepageContent?->hero_image_path ? asset('storage/'.$homepageContent->hero_image_path) : null;

        $kitImages = [
            'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=960&q=80',
            'https://images.unsplash.com/photo-1581092921461-39b9d08a9b2e?auto=format&fit=crop&w=960&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=960&q=80',
            'https://images.unsplash.com/photo-1518773553398-650c184e0bb3?auto=format&fit=crop&w=960&q=80',
        ];

        $jobListings = [
            ['title' => 'Satellite Installation Technician', 'desc' => 'Install and align satellite dishes for optimal connectivity.', 'icon' => 'fa-satellite-dish'],
            ['title' => 'Customer Support Specialist', 'desc' => 'Provide 24/7 support to our Kenyan customer base.', 'icon' => 'fa-headset'],
            ['title' => 'Digital Marketer', 'desc' => 'Drive our online presence across social and search channels.', 'icon' => 'fa-bullhorn'],
        ];

        $serviceCards = [
            ['title' => 'Sell Starlink Kits in Kenya', 'desc' => 'We provide an easy and reliable way for you to purchase, sell, or install Starlink internet kits across the country.'],
            ['title' => 'Starlink Installation Services in Kenya', 'desc' => 'Certified installers offering seamless setup and activation of Starlink satellite internet across Kenya.'],
            ['title' => 'CCTV Installation Services in Kenya', 'desc' => 'Secure your home and business with professional CCTV planning, installation, and maintenance.'],
            ['title' => 'Networking Solutions and Installation Services', 'desc' => 'LAN, Wi-Fi, and business networking design with practical deployment for Kenyan homes and offices.'],
        ];

        $cartCount = (int) collect(session('cart', []))
            ->sum(fn (array $item): int => (int) ($item['quantity'] ?? 0));

        $rootBaseUrl = request()->getSchemeAndHttpHost();
        $loginEntryUrl = $rootBaseUrl.'/login.php';
        $dashboardEntryUrl = $rootBaseUrl.'/dashboard.php';
    @endphp

    <style>
        :root {
            --page-bg: #f4f7fc;
            --surface: #ffffff;
            --surface-soft: #eef6ff;
            --surface-soft-2: #f8fbff;
            --ink-900: #0d1c3d;
            --ink-700: #243b64;
            --ink-500: #586f91;
            --line: #d9e6f4;
            --line-strong: #c7dbee;
            --orange: #ff9b2f;
            --orange-dark: #eb8111;
            --orange-soft: #fff2df;
            --sky: #d9ecff;
            --sky-strong: #8bd9ff;
            --shadow-soft: 0 20px 54px rgba(15, 37, 79, 0.08);
            --shadow-card: 0 14px 32px rgba(14, 37, 79, 0.06);
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(188, 222, 255, 0.44) 0%, transparent 24%),
                linear-gradient(180deg, #f8fbff 0%, var(--page-bg) 100%);
            font-family: 'Manrope', sans-serif;
        }

        .home {
            color: var(--ink-900);
            overflow-x: clip;
            position: relative;
            isolation: isolate;
        }

        .home::before,
        .home::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .home::before {
            width: 540px;
            height: 540px;
            top: -190px;
            right: -180px;
            background: radial-gradient(circle, rgba(203, 231, 255, 0.72) 0%, rgba(203, 231, 255, 0) 70%);
        }

        .home::after {
            width: 400px;
            height: 400px;
            top: 320px;
            left: -220px;
            background: radial-gradient(circle, rgba(255, 185, 104, 0.18) 0%, rgba(255, 185, 104, 0) 72%);
        }

        .container {
            width: min(1320px, 92vw);
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .store-header {
            background: rgba(248, 251, 255, 0.78);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(217, 230, 244, 0.92);
        }

        .notice-bar,
        .main-bar {
            background: transparent;
        }

        .notice-inner {
            min-height: 72px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            padding: 14px 0 10px;
        }

        .notice-left {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 22px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.78);
            color: var(--ink-700);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            box-shadow: 0 10px 24px rgba(14, 37, 79, 0.04);
        }

        .notice-right {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--ink-700);
            font-size: 15px;
        }

        .notice-right a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.8);
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
            box-shadow: 0 10px 24px rgba(14, 37, 79, 0.04);
        }

        .notice-right a:hover {
            transform: translateY(-1px);
            border-color: var(--line-strong);
            box-shadow: 0 14px 28px rgba(14, 37, 79, 0.08);
        }

        .main-bar-inner {
            min-height: 104px;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 28px;
            padding: 8px 0 24px;
        }

        .brand-block {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            min-width: 296px;
        }

        .brand-mark {
            position: relative;
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #112447 0%, #1f3a6d 100%);
            color: #fff;
            font-size: 28px;
            box-shadow: 0 16px 30px rgba(17, 36, 71, 0.24);
        }

        .brand-mark::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            right: 10px;
            top: 10px;
            border-radius: 50%;
            background: var(--orange);
            box-shadow: 0 0 0 6px rgba(255, 155, 47, 0.12);
        }

        .brand-text {
            line-height: 1;
        }

        .brand-text strong {
            display: block;
            font-size: clamp(28px, 2.4vw, 42px);
            font-weight: 800;
            color: var(--ink-900);
            letter-spacing: .02em;
        }

        .brand-text small {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-500);
            letter-spacing: .24em;
        }

        .header-search {
            width: 100%;
            display: flex;
            align-items: stretch;
            border: 1px solid var(--line);
            border-radius: 26px;
            overflow: hidden;
            background: var(--surface);
            box-shadow: var(--shadow-soft);
        }

        .header-search input {
            flex: 1;
            border: 0;
            background: transparent;
            color: var(--ink-900);
            height: 72px;
            padding: 0 28px;
            font-size: 17px;
            font-family: inherit;
            outline: none;
        }

        .header-search input::placeholder {
            color: var(--ink-500);
        }

        .header-search button {
            border: 0;
            min-width: 78px;
            padding: 0 24px;
            background: linear-gradient(135deg, var(--orange) 0%, #ffb14f 100%);
            color: #fff;
            font-size: 18px;
            display: grid;
            place-items: center;
            cursor: pointer;
            box-shadow: -10px 0 24px rgba(255, 155, 47, 0.2);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: var(--ink-900);
            font-size: 20px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 10px 24px rgba(14, 37, 79, 0.05);
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, color .2s ease;
        }

        .header-icon:hover {
            transform: translateY(-1px);
            color: var(--orange-dark);
            border-color: rgba(255, 155, 47, 0.24);
            box-shadow: 0 14px 28px rgba(14, 37, 79, 0.08);
        }

        .cart-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 24px;
            height: 24px;
            border-radius: 999px;
            border: 2px solid var(--surface);
            background: var(--orange);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: grid;
            place-items: center;
            padding: 0 6px;
        }

        .chevron-icon {
            width: 48px;
        }

        .section-nav {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin: 20px 0 28px;
            padding: 12px;
            border-radius: 24px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.82);
            box-shadow: 0 14px 28px rgba(14, 37, 79, 0.04);
        }

        .section-nav a {
            border-radius: 18px;
            padding: 12px 18px;
            color: var(--ink-700);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .01em;
            transition: .2s ease;
        }

        .section-nav a:hover {
            background: var(--orange-soft);
            color: var(--orange-dark);
            transform: translateY(-1px);
        }

        .section-anchor {
            display: block;
            height: 0;
            scroll-margin-top: 24px;
        }

        .btn {
            border-radius: 999px;
            padding: 16px 28px;
            border: 1px solid transparent;
            font-size: 17px;
            font-weight: 700;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover,
        .view-btn:hover,
        .apply-btn:hover,
        .field-submit:hover {
            transform: translateY(-2px);
        }

        .btn-outline {
            border-color: var(--line);
            color: var(--ink-900);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 12px 24px rgba(14, 37, 79, 0.04);
        }

        .btn-outline:hover {
            border-color: rgba(255, 155, 47, 0.26);
            background: var(--surface);
        }

        .btn-orange,
        .view-btn,
        .field-submit {
            background: linear-gradient(135deg, var(--orange) 0%, #ffb14f 100%);
            color: #fff;
            box-shadow: 0 16px 32px rgba(255, 155, 47, 0.24);
        }

        .hero-welcome-badge,
        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 11px 22px;
            background: rgba(230, 236, 244, 0.92);
            border: 1px solid rgba(196, 208, 224, 0.8);
            color: var(--ink-700);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .hero {
            padding: 28px 0 72px;
            display: grid;
            grid-template-columns: minmax(0, .92fr) minmax(0, 1.08fr);
            align-items: center;
            gap: 52px;
        }

        .hero h1 {
            margin: 18px 0 0;
            font-size: clamp(48px, 5vw, 72px);
            line-height: .98;
            letter-spacing: -.05em;
            font-weight: 800;
            max-width: 10ch;
        }

        .hero h1 strong {
            color: var(--orange-dark);
        }

        .hero p {
            margin: 20px 0 0;
            max-width: 30ch;
            font-size: clamp(19px, 1.5vw, 24px);
            color: var(--ink-500);
            line-height: 1.55;
        }

        .hero-actions {
            display: flex;
            gap: 18px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .hero-banner {
            min-height: 520px;
            position: relative;
            overflow: hidden;
            border-radius: 40px;
            border: 1px solid rgba(31, 58, 109, 0.12);
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow:
                18px 18px 0 #22384c,
                0 38px 80px rgba(14, 37, 79, 0.14);
        }

        .hero-banner::before,
        .hero-banner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-banner::before {
            width: 220px;
            height: 220px;
            top: -70px;
            left: -70px;
            background: radial-gradient(circle, rgba(255, 155, 47, 0.3) 0%, rgba(255, 155, 47, 0) 72%);
        }

        .hero-banner::after {
            width: 320px;
            height: 320px;
            top: -76px;
            right: -144px;
            background: radial-gradient(circle, rgba(140, 217, 255, 0.34) 0%, rgba(140, 217, 255, 0) 70%);
        }

        .hero-banner.has-image {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .hero-banner-image {
            position: absolute;
            inset: 24px;
            width: calc(100% - 48px);
            height: calc(100% - 48px);
            object-fit: contain;
            border-radius: 28px;
            background: #fff;
            z-index: 1;
        }

        .dish-shape {
            position: absolute;
            inset: 56px 52px;
            border-radius: 32px;
            border: 1px solid rgba(31, 58, 109, 0.12);
            background:
                radial-gradient(circle at 18% 78%, rgba(140, 217, 255, 0.26) 0 56px, transparent 57px),
                radial-gradient(circle at 78% 24%, rgba(255, 155, 47, 0.12) 0 62px, transparent 63px),
                linear-gradient(135deg, #ffffff 0%, #f2f8ff 100%);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.78);
        }

        .dish-shape::before {
            content: '';
            position: absolute;
            left: 12%;
            top: 22%;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--ink-900);
            box-shadow:
                180px 104px 0 var(--ink-900),
                432px 88px 0 var(--ink-900),
                548px 204px 0 var(--ink-900);
        }

        .dish-shape::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(32deg, transparent 35.5%, rgba(13, 28, 61, 0.3) 36%, rgba(13, 28, 61, 0.3) 36.6%, transparent 37%) 0 0 / 100% 100% no-repeat,
                linear-gradient(-24deg, transparent 50.2%, rgba(13, 28, 61, 0.3) 50.6%, rgba(13, 28, 61, 0.3) 51%, transparent 51.4%) 0 0 / 100% 100% no-repeat,
                linear-gradient(24deg, transparent 56.5%, rgba(13, 28, 61, 0.3) 56.9%, rgba(13, 28, 61, 0.3) 57.3%, transparent 57.7%) 0 0 / 100% 100% no-repeat;
            opacity: .9;
        }

        .section {
            padding: 72px 0;
        }

        .section-title {
            margin: 0;
            font-size: clamp(34px, 3vw, 50px);
            line-height: 1.08;
            letter-spacing: -.04em;
        }

        .section-intro {
            margin: 18px 0 0;
            font-size: clamp(18px, 1.2vw, 21px);
            color: var(--ink-500);
            max-width: 62ch;
            line-height: 1.65;
        }

        .products-grid {
            margin-top: 36px;
            display: grid;
            gap: 24px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .product-card {
            border-radius: 32px;
            border: 1px solid var(--line);
            background: var(--surface);
            padding: 18px;
            box-shadow: var(--shadow-card);
            display: flex;
            flex-direction: column;
        }

        .product-image {
            height: 260px;
            width: 100%;
            object-fit: cover;
            display: block;
            border-radius: 26px;
            background: var(--surface-soft);
        }

        .product-body {
            padding: 24px 6px 8px;
            text-align: center;
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        .product-name {
            margin: 0;
            font-size: 22px;
            line-height: 1.24;
            font-weight: 700;
            color: var(--ink-900);
        }

        .product-desc {
            margin: 14px 0 0;
            color: var(--ink-500);
            font-size: 16px;
            line-height: 1.65;
            min-height: 82px;
        }

        .product-bottom {
            margin-top: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            padding-top: 20px;
        }

        .price {
            font-size: 22px;
            font-weight: 700;
            color: var(--ink-900);
        }

        .view-btn {
            padding: 13px 24px;
            border-radius: 999px;
            font-size: 16px;
            font-weight: 700;
        }

        .discover {
            background: transparent;
        }

        .discover-grid {
            display: grid;
            grid-template-columns: .92fr 1.08fr;
            gap: 42px;
            align-items: center;
            padding: 46px;
            border-radius: 40px;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, rgba(236, 246, 255, 0.92) 0%, rgba(255, 255, 255, 0.98) 100%);
            box-shadow: var(--shadow-soft);
        }

        .discover-list {
            margin: 26px 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 14px;
            color: var(--ink-700);
            font-size: 18px;
        }

        .discover-list li i {
            color: var(--orange);
            margin-right: 10px;
        }

        .video-wrap {
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid var(--line);
            box-shadow: var(--shadow-card);
            background: #0c1425;
        }

        .video-wrap iframe {
            width: 100%;
            height: 400px;
            border: 0;
            display: block;
        }

        .pill {
            margin-bottom: 16px;
        }

        .services-grid {
            margin-top: 34px;
            display: grid;
            gap: 22px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .service-card {
            border-radius: 30px;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 30px;
            box-shadow: var(--shadow-card);
            display: flex;
            flex-direction: column;
        }

        .service-card h3 {
            margin: 0;
            font-size: 28px;
            line-height: 1.18;
        }

        .service-card p {
            margin: 16px 0 0;
            font-size: 18px;
            line-height: 1.72;
            color: var(--ink-500);
            flex: 1;
        }

        .service-card .btn-orange {
            margin-top: 24px;
            justify-content: center;
        }

        .career-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.04fr) minmax(0, .96fr);
            gap: 28px;
            align-items: start;
        }

        .career-title {
            margin: 0 0 22px;
            font-size: clamp(34px, 2.7vw, 46px);
            letter-spacing: -.04em;
        }

        .job-list {
            display: grid;
            gap: 18px;
        }

        .job-item {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 22px 24px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 18px;
            align-items: center;
            box-shadow: var(--shadow-card);
        }

        .job-icon {
            font-size: 26px;
            color: var(--orange-dark);
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: var(--orange-soft);
        }

        .job-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
        }

        .job-desc {
            margin: 10px 0 0;
            color: var(--ink-500);
            font-size: 16px;
            line-height: 1.6;
        }

        .apply-btn {
            border: 1px solid var(--line);
            color: var(--ink-900);
            border-radius: 999px;
            background: var(--surface-soft-2);
            font-weight: 700;
            padding: 13px 22px;
            font-size: 15px;
            box-shadow: 0 10px 22px rgba(14, 37, 79, 0.04);
        }

        .apply-panel {
            background: linear-gradient(180deg, rgba(236, 246, 255, 0.94) 0%, #ffffff 100%);
            border: 1px solid var(--line);
            border-radius: 32px;
            padding: 30px;
            box-shadow: var(--shadow-soft);
        }

        .apply-panel form {
            display: grid;
            gap: 14px;
        }

        .field,
        .field-select,
        .field-file {
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px 18px;
            font-size: 17px;
            font-family: inherit;
            width: 100%;
            background: rgba(255, 255, 255, 0.94);
            color: var(--ink-900);
        }

        .field-file {
            padding: 10px;
        }

        .field:focus,
        .field-select:focus,
        .field-file:focus {
            outline: none;
            border-color: rgba(255, 155, 47, 0.45);
            box-shadow: 0 0 0 4px rgba(255, 155, 47, 0.1);
        }

        .resume-label {
            font-size: 15px;
            font-weight: 600;
            color: var(--ink-500);
            margin-top: 2px;
        }

        .field-submit {
            margin-top: 8px;
            border: 0;
            border-radius: 999px;
            padding: 16px 24px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
        }

        .article-wrap {
            background: transparent;
            padding: 0;
            box-shadow: none;
        }

        .article-box {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 40px;
            background: var(--surface);
            padding: 58px 64px;
            box-shadow: var(--shadow-soft);
        }

        .article-box::before {
            content: '';
            position: absolute;
            left: 0;
            top: 36px;
            bottom: 36px;
            width: 6px;
            border-radius: 999px;
            background: linear-gradient(180deg, var(--orange) 0%, var(--sky-strong) 100%);
        }

        .article-box::after {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            top: -120px;
            right: -90px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 155, 47, 0.16) 0%, rgba(255, 155, 47, 0) 72%);
        }

        .article-box h2 {
            margin: 0;
            font-size: clamp(34px, 3vw, 54px);
            line-height: 1.08;
            color: var(--ink-900);
            letter-spacing: -.05em;
        }

        .article-line {
            width: 76px;
            height: 5px;
            margin: 20px 0 24px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--orange) 0%, var(--sky-strong) 100%);
        }

        .article-box p {
            margin: 0;
            font-size: 20px;
            color: var(--ink-500);
            line-height: 1.75;
        }

        .article-box h3 {
            margin: 0 0 14px;
            color: var(--ink-900);
            font-size: 32px;
            line-height: 1.14;
            letter-spacing: -.03em;
        }

        .article-box ul,
        .article-box ol {
            margin: 0;
            padding-left: 28px;
            color: var(--ink-500);
            font-size: 20px;
            line-height: 1.75;
        }

        .article-box li + li {
            margin-top: 10px;
        }

        .article-box * + h2,
        .article-box * + h3,
        .article-box * + ul,
        .article-box * + ol,
        .article-box * + p {
            margin-top: 22px;
        }

        .footer {
            border-top: 1px solid var(--line);
            color: var(--ink-500);
            text-align: center;
            font-size: 15px;
            padding: 28px 0 42px;
        }

        .whatsapp-wrap {
            position: fixed;
            right: 26px;
            bottom: 22px;
            z-index: 40;
            display: grid;
            gap: 10px;
            justify-items: end;
        }

        .whatsapp-tip {
            background: var(--ink-900);
            color: #fff;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 14px;
            box-shadow: 0 14px 24px rgba(13, 28, 61, 0.18);
        }

        .whatsapp-btn {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 0;
            display: grid;
            place-items: center;
            font-size: 34px;
            background: #25d366;
            color: #fff;
            box-shadow: 8px 8px 0 rgba(255, 155, 47, 0.88);
        }

        @media (max-width: 1280px) {
            .products-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1120px) {
            .hero,
            .discover-grid,
            .career-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                gap: 34px;
            }

            .hero h1,
            .hero p {
                max-width: none;
            }

            .hero-banner {
                min-height: 460px;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 980px) {
            .main-bar-inner {
                grid-template-columns: 1fr;
                justify-items: stretch;
                gap: 18px;
                padding-bottom: 22px;
            }

            .brand-block,
            .header-actions {
                justify-self: center;
            }

            .products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .article-box {
                padding: 44px 38px;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: min(1320px, 94vw);
            }

            .notice-inner {
                min-height: auto;
                flex-wrap: wrap;
                padding: 12px 0;
            }

            .notice-left {
                font-size: 12px;
                padding: 10px 16px;
            }

            .notice-right {
                width: 100%;
                justify-content: flex-start;
                gap: 10px;
                overflow-x: auto;
            }

            .notice-right a {
                white-space: nowrap;
                padding: 10px 14px;
                font-size: 14px;
            }

            .brand-mark {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                font-size: 24px;
            }

            .brand-text strong {
                font-size: 25px;
            }

            .brand-text small {
                font-size: 10px;
                letter-spacing: .2em;
            }

            .header-search {
                border-radius: 22px;
            }

            .header-search input {
                height: 60px;
                padding: 0 18px;
                font-size: 15px;
            }

            .header-search button {
                min-width: 64px;
                padding: 0 18px;
            }

            .header-icon,
            .chevron-icon {
                width: 44px;
                height: 44px;
                border-radius: 16px;
            }

            .section-nav {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding: 10px;
            }

            .section-nav a {
                white-space: nowrap;
                padding: 11px 14px;
            }

            .hero {
                padding: 14px 0 56px;
            }

            .hero h1 {
                font-size: 42px;
            }

            .hero p,
            .section-intro,
            .article-box p,
            .article-box ul,
            .article-box ol {
                font-size: 18px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .hero-actions {
                width: 100%;
            }

            .hero-banner {
                min-height: 360px;
                border-radius: 30px;
                box-shadow:
                    10px 10px 0 #22384c,
                    0 26px 52px rgba(14, 37, 79, 0.12);
            }

            .dish-shape {
                inset: 28px 24px;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .product-image {
                height: 230px;
            }

            .discover-grid,
            .apply-panel,
            .article-box {
                padding: 26px;
            }

            .job-item {
                grid-template-columns: 1fr;
                justify-items: start;
            }

            .video-wrap iframe {
                height: 260px;
            }

            .whatsapp-wrap {
                right: 16px;
                bottom: 16px;
            }

            .whatsapp-btn {
                width: 58px;
                height: 58px;
                font-size: 28px;
                box-shadow: 6px 6px 0 rgba(255, 155, 47, 0.84);
            }
        }
    </style>

    <main class="home">
        <header class="store-header">
            <div class="notice-bar">
                <div class="container notice-inner">
                    <div class="notice-left">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>top_notice</span>
                    </div>
                    <div class="notice-right">
                        <a href="#" aria-label="Wishlist">
                            <i class="fa-solid fa-heart"></i>
                            Wishlist
                        </a>
                        <a href="{{ $loginEntryUrl }}" aria-label="Sign in">
                            <i class="fa-solid fa-user"></i>
                            Sign in
                        </a>
                        <a href="tel:+254701299299" aria-label="Call 0701299299">
                            <i class="fa-solid fa-headset"></i>
                            0701299299
                        </a>
                    </div>
                </div>
            </div>
            <div class="main-bar">
                <div class="container main-bar-inner">
                    <a class="brand-block" href="{{ route('home') }}" aria-label="Starlink Kenya Installers home">
                        <span class="brand-mark"><i class="fa-solid fa-satellite-dish"></i></span>
                        <span class="brand-text">
                            <strong>STARLINK</strong>
                            <small>KENYA INSTALLERS</small>
                        </span>
                    </a>
                    <form class="header-search" action="{{ route('home') }}" method="GET" role="search">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search for products..." aria-label="Search for products">
                        <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                    <div class="header-actions">
                        <a class="header-icon" href="#" aria-label="Wishlist">
                            <i class="fa-solid fa-heart"></i>
                        </a>
                        <a class="header-icon" href="{{ route('shop.cart.index') }}" aria-label="Cart">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart-badge">{{ $cartCount }}</span>
                        </a>
                        <a class="header-icon" href="{{ auth()->check() ? $dashboardEntryUrl : $loginEntryUrl }}" aria-label="Account">
                            <i class="fa-solid fa-user"></i>
                        </a>
                        <a class="header-icon chevron-icon" href="{{ auth()->check() ? $dashboardEntryUrl : $loginEntryUrl }}" aria-label="Account menu">
                            <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="container">
            <nav class="section-nav" aria-label="Page section navigation">
                <a href="#packages">Starlink Kenya Packages</a>
                <a href="#prices">Starlink Kenya Prices</a>
                <a href="#order-now">Order Now</a>
                <a href="#installation">Installation</a>
            </nav>

            <section class="hero">
                <div>
                    <span class="hero-welcome-badge">Welcome to Starlink Kenya Installers</span>
                    <h1>{{ $heroTitle }}</h1>
                    <p>{{ $heroDescription }}</p>
                    <div class="hero-actions">
                        <a class="btn btn-orange" href="#packages">Shop Now</a>
                        <a class="btn btn-outline" href="tel:+254700123456">Talk to an Expert</a>
                    </div>
                </div>
                <div class="hero-banner {{ $heroImageUrl ? 'has-image' : '' }}" aria-hidden="true">
                    @if ($heroImageUrl)
                        <img class="hero-banner-image" src="{{ $heroImageUrl }}" alt="Starlink Kenya Hero">
                    @else
                        <div class="dish-shape"></div>
                    @endif
                </div>
            </section>

            <section id="packages" class="section">
                <span id="kits" class="section-anchor" aria-hidden="true"></span>
                <span id="prices" class="section-anchor" aria-hidden="true"></span>
                <h2 class="section-title">{{ $productsTitle }}</h2>
                <p class="section-intro">Explore our genuine Starlink hardware and accessories.</p>
                <div class="products-grid">
                    @forelse ($products as $product)
                        <article class="product-card">
                            <img class="product-image" src="{{ $kitImages[$loop->index % count($kitImages)] }}" alt="{{ $product->name }}">
                            <div class="product-body">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-desc">
                                    {{ $product->name }} delivers reliable low-latency satellite internet across Kenya.
                                </p>
                                <div class="product-bottom">
                                    <span class="price">KES {{ number_format((float) $product->price, 2) }}</span>
                                    <a class="view-btn" href="{{ route('shop.product.show', $product) }}">View</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="product-card">
                            <div class="product-body">
                                <h3 class="product-name">No products published yet</h3>
                                <p class="product-desc">Login to the admin dashboard and add Starlink kits.</p>
                            </div>
                        </article>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="section discover">
            <div class="container">
                <div class="discover-grid">
                    <div>
                        <h2 class="section-title">{{ $whyChooseTitle }}</h2>
                        <p class="section-intro">{{ $whyChooseDescription }}</p>
                        <ul class="discover-list">
                            <li><i class="fa-solid fa-check"></i> Speeds up to 220 Mbps</li>
                            <li><i class="fa-solid fa-check"></i> Optimized for Kenyan terrain</li>
                            <li><i class="fa-solid fa-check"></i> DIY or Pro install</li>
                        </ul>
                        <div class="hero-actions">
                            <a class="btn btn-orange" href="#installation">Get Started</a>
                        </div>
                    </div>
                    <div class="video-wrap">
                        <iframe
                            src="https://www.youtube.com/embed/y4j-B6Vf8vo"
                            title="Starlink Kenya Installers"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </section>

        <section id="installation" class="section">
            <span id="services" class="section-anchor" aria-hidden="true"></span>
            <div class="container">
                <div style="text-align:center;">
                    <span class="pill">Our Services</span>
                    <h2 class="section-title">Explore Our Starlink Kenya Solutions</h2>
                </div>
                <div class="services-grid">
                    @foreach ($serviceCards as $service)
                        <article class="service-card">
                            <h3>{{ $service['title'] }}</h3>
                            <p>{{ $service['desc'] }}</p>
                            <a class="btn btn-orange" href="#">Learn More</a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="order-now" class="section">
            <div class="container">
                <div class="career-grid">
                    <div>
                        <h2 class="career-title">Join Our Team</h2>
                        <div class="job-list">
                            @foreach ($jobListings as $job)
                                <article class="job-item">
                                    <i class="fa-solid {{ $job['icon'] }} job-icon"></i>
                                    <div>
                                        <h3 class="job-title">{{ $job['title'] }}</h3>
                                        <p class="job-desc">{{ $job['desc'] }}</p>
                                    </div>
                                    <button type="button" class="apply-btn">Apply</button>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h2 class="career-title">Apply Now</h2>
                        <div class="apply-panel">
                            <form>
                                <input class="field" type="text" placeholder="Full Name">
                                <input class="field" type="email" placeholder="Email Address">
                                <select class="field-select">
                                    <option>Choose a position</option>
                                    <option>Satellite Installation Technician</option>
                                    <option>Customer Support Specialist</option>
                                    <option>Digital Marketer</option>
                                </select>
                                <label class="resume-label">Upload Resume (PDF)</label>
                                <input class="field-file" type="file" accept=".pdf">
                                <button class="field-submit" type="button">Submit Application</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="article-wrap">
                    <div class="article-box">
                        <div class="article-line"></div>
                        {!! $homePageContentHtml !!}
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            Copyright {{ now()->year }} Starlink Kenya Installers. All rights reserved.
        </footer>

        <div class="whatsapp-wrap">
            <div class="whatsapp-tip">Chat with us on WhatsApp</div>
            <a class="whatsapp-btn" href="https://wa.me/254700123456" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        </div>
    </main>
@endsection

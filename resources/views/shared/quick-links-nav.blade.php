@php
    $useHashLinks = $useHashLinks ?? false;
    $homeUrl = route('home');
    $quickLinks = [
        [
            'label' => 'Starlink Kenya Packages',
            'href' => $useHashLinks ? '#packages' : $homeUrl.'#packages',
        ],
        [
            'label' => 'Starlink Kenya Prices',
            'href' => $useHashLinks ? '#prices' : $homeUrl.'#prices',
        ],
        [
            'label' => 'Order Now',
            'href' => $useHashLinks ? '#order-now' : $homeUrl.'#order-now',
        ],
        [
            'label' => 'Installation',
            'href' => $useHashLinks ? '#installation' : $homeUrl.'#installation',
        ],
    ];
@endphp

@once
    <style>
        .quick-links-nav {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin: 0 0 24px;
            padding: 14px 16px;
            border-radius: 32px;
            border: 1px solid #d8e6f4;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 18px 36px rgba(14, 37, 79, 0.06);
        }

        .quick-links-nav__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            padding: 14px 24px;
            color: #233f6c;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            transition: transform .2s ease, background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .quick-links-nav__link:hover {
            background: #fff3e2;
            color: #eb8111;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(255, 155, 47, 0.12);
        }

        @media (max-width: 760px) {
            .quick-links-nav {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding: 10px 12px;
                margin-bottom: 20px;
                scrollbar-width: none;
            }

            .quick-links-nav::-webkit-scrollbar {
                display: none;
            }

            .quick-links-nav__link {
                padding: 12px 18px;
                font-size: 14px;
            }
        }
    </style>
@endonce

<nav class="quick-links-nav" aria-label="{{ $navLabel ?? 'Quick site links' }}">
    @foreach ($quickLinks as $quickLink)
        <a class="quick-links-nav__link" href="{{ $quickLink['href'] }}">{{ $quickLink['label'] }}</a>
    @endforeach
</nav>

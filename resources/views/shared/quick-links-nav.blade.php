@php
    $useHashLinks = $useHashLinks ?? false;
    $variant = $variant ?? 'pill';
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

        .quick-links-nav--inline {
            gap: 18px 34px;
            margin: 0 0 32px;
            padding: 0 4px 10px;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .quick-links-nav--inline .quick-links-nav__link {
            justify-content: flex-start;
            padding: 10px 0;
            border-radius: 0;
            border-bottom: 3px solid transparent;
            color: #163860;
            font-size: clamp(18px, 1.4vw, 22px);
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .quick-links-nav--inline .quick-links-nav__link:hover,
        .quick-links-nav--inline .quick-links-nav__link.is-active {
            background: transparent;
            color: #0c2749;
            transform: none;
            box-shadow: none;
        }

        .quick-links-nav--inline .quick-links-nav__link.is-active {
            border-color: #183a63;
        }

        .quick-links-nav--inline .quick-links-nav__link:hover {
            border-color: #ff9b2f;
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

            .quick-links-nav--inline {
                padding: 0 0 8px;
                margin-bottom: 24px;
            }

            .quick-links-nav--inline .quick-links-nav__link {
                padding: 10px 0;
                font-size: 16px;
            }
        }
    </style>
@endonce

<nav class="quick-links-nav {{ $variant === 'inline' ? 'quick-links-nav--inline' : '' }}" aria-label="{{ $navLabel ?? 'Quick site links' }}">
    @foreach ($quickLinks as $quickLink)
        <a class="quick-links-nav__link {{ $variant === 'inline' && $loop->first ? 'is-active' : '' }}" href="{{ $quickLink['href'] }}">{{ $quickLink['label'] }}</a>
    @endforeach
</nav>

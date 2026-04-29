@extends('layouts.app', ['title' => 'Your Cart | Starlink Kenya Installers'])

@section('content')
    <style>
        .cart-page {
            min-height: 100vh;
            padding: 42px 0 56px;
        }

        .container {
            width: min(1180px, 92vw);
            margin: 0 auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 26px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.92);
            color: var(--ink);
            font-size: 15px;
            font-weight: 600;
            padding: 12px 18px;
            box-shadow: 0 10px 24px rgba(14, 37, 79, 0.05);
        }

        .flash {
            margin-bottom: 12px;
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #bfdcc8;
            background: #eefaf2;
            color: #21633b;
            font-weight: 600;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            gap: 24px;
        }

        .items-card,
        .summary-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 24px;
            box-shadow: var(--shadow);
        }

        .item {
            display: grid;
            grid-template-columns: 120px minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
            border-bottom: 1px solid #f1e5d6;
            padding: 18px 0;
        }

        .item:last-child {
            border-bottom: 0;
        }

        .item img {
            width: 120px;
            height: 92px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: var(--panel);
        }

        .item-name {
            margin: 0;
            font-size: 22px;
            color: var(--ink);
        }

        .item-meta {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .item-total {
            font-size: 22px;
            color: var(--brand-dark);
            font-weight: 800;
            text-align: right;
        }

        .remove-btn {
            margin-top: 8px;
            border: 1px solid #efc7ca;
            background: #fff5f4;
            color: #a33b43;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 14px;
            cursor: pointer;
        }

        .summary-title {
            margin: 0;
            font-size: 32px;
            color: var(--ink);
        }

        .count {
            margin-top: 10px;
            color: var(--muted);
            font-weight: 600;
            font-size: 16px;
        }

        .total {
            margin-top: 20px;
            font-size: 42px;
            color: var(--brand-dark);
            letter-spacing: -0.05em;
            font-weight: 800;
        }

        .checkout-btn {
            margin-top: 16px;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 999px;
            padding: 16px 18px;
            background: #21b463;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 14px 28px rgba(33, 180, 99, 0.2);
        }

        .empty {
            margin: 0;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
        }

        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .item {
                grid-template-columns: 1fr;
                align-items: start;
            }

            .item-total {
                text-align: left;
            }
        }
    </style>

    <main class="cart-page">
        <div class="container">
            <div class="top-bar">
                <a class="back-link" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Continue Shopping</a>
            </div>

            @if (session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            <section class="layout">
                <article class="items-card">
                    @if ($items->isEmpty())
                        <p class="empty">Your cart is empty. Open a product and click Add to Cart to start ordering.</p>
                    @else
                        @foreach ($items as $item)
                            <div class="item">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}">

                                <div>
                                    <h2 class="item-name">{{ $item['name'] }}</h2>
                                    <p class="item-meta">
                                        Qty: {{ (int) $item['quantity'] }} |
                                        Unit: KES {{ number_format((float) $item['price'], 2) }}
                                    </p>
                                </div>

                                <div>
                                    <div class="item-total">KES {{ number_format((float) $item['line_total'], 2) }}</div>
                                    <form method="POST" action="{{ route('shop.cart.remove', ['product' => $item['product_id']]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="remove-btn" type="submit">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </article>

                <aside class="summary-card">
                    <h1 class="summary-title">Cart Summary</h1>
                    <div class="count">Items: {{ $cartCount }}</div>
                    <div class="total">KES {{ number_format($total, 2) }}</div>

                    <a class="checkout-btn" href="{{ $checkoutWhatsappUrl }}" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp"></i> Checkout on WhatsApp
                    </a>
                </aside>
            </section>
        </div>
    </main>
@endsection

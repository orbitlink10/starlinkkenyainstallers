@php
    $active = $activeSection ?? 'dashboard';
    $menuItems = [
        ['key' => 'categories', 'label' => 'Categories', 'icon' => 'fa-table-list', 'route' => route('categories.index')],
        ['key' => 'sub-categories', 'label' => 'Sub Categories', 'icon' => 'fa-tags', 'route' => route('admin.section', ['section' => 'sub-categories'])],
        ['key' => 'products', 'label' => 'Products', 'icon' => 'fa-boxes-stacked', 'route' => route('products.index')],
        ['key' => 'orders', 'label' => 'Orders', 'icon' => 'fa-cart-shopping', 'route' => route('admin.section', ['section' => 'orders'])],
        ['key' => 'invoices', 'label' => 'Invoices', 'icon' => 'fa-file-invoice', 'route' => route('admin.section', ['section' => 'invoices'])],
    ];

    $adminItems = [
        ['key' => 'users', 'label' => 'Users', 'icon' => 'fa-users', 'route' => route('admin.section', ['section' => 'users'])],
        ['key' => 'homepage-content', 'label' => 'Homepage Content', 'icon' => 'fa-file-lines', 'route' => route('admin.section', ['section' => 'homepage-content'])],
        ['key' => 'sliders', 'label' => 'Sliders', 'icon' => 'fa-images', 'route' => route('admin.section', ['section' => 'sliders'])],
        ['key' => 'pages', 'label' => 'Pages', 'icon' => 'fa-pen-to-square', 'route' => route('pages.index')],
        ['key' => 'services', 'label' => 'Services', 'icon' => 'fa-screwdriver-wrench', 'route' => route('admin.section', ['section' => 'services'])],
        ['key' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fa-message', 'route' => route('admin.section', ['section' => 'testimonials'])],
        ['key' => 'media', 'label' => 'Media', 'icon' => 'fa-photo-film', 'route' => route('admin.section', ['section' => 'media'])],
        ['key' => 'menus', 'label' => 'Menus', 'icon' => 'fa-bars-staggered', 'route' => route('admin.section', ['section' => 'menus'])],
        ['key' => 'settings', 'label' => 'Settings', 'icon' => 'fa-gears', 'route' => route('admin.section', ['section' => 'settings'])],
        ['key' => 'enquiries', 'label' => 'Enquiries', 'icon' => 'fa-bell', 'route' => route('admin.section', ['section' => 'enquiries'])],
    ];
@endphp

<aside class="sidebar">
    <a class="brand" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">Starlink Kenya</a>

    <div class="menu-block">
        <a class="menu-link {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <span class="menu-icon"><i class="fa-solid fa-gauge-high"></i></span>
            Dashboard
        </a>
    </div>

    <div class="menu-block">
        <h3 class="menu-title">Content Management</h3>
        @foreach ($menuItems as $item)
            <a class="menu-link {{ $active === $item['key'] ? 'active' : '' }}" href="{{ $item['route'] }}">
                <span class="menu-icon"><i class="fa-solid {{ $item['icon'] }}"></i></span>{{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <div class="menu-block">
        <h3 class="menu-title">Admin Panel</h3>
        @foreach ($adminItems as $item)
            <a class="menu-link {{ $active === $item['key'] ? 'active' : '' }}" href="{{ $item['route'] }}">
                <span class="menu-icon"><i class="fa-solid {{ $item['icon'] }}"></i></span>{{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <div class="menu-block">
        <h3 class="menu-title">Account</h3>
        <a class="menu-link {{ $active === 'profile' ? 'active' : '' }}" href="{{ route('admin.section', ['section' => 'profile']) }}">
            <span class="menu-icon"><i class="fa-solid fa-user-pen"></i></span>Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="menu-logout" type="submit">
                <span class="menu-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                Logout
            </button>
        </form>
    </div>
</aside>

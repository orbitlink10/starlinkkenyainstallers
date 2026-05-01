@php
    $active = $activeSection ?? 'dashboard';
    $menuLinkBase = 'menu-link group flex w-full items-center gap-3 rounded-[0.95rem] px-2.5 py-2.5 text-left text-[0.95rem] font-semibold leading-[1.25] transition duration-200';
    $menuLinkInactive = 'text-[#365378] hover:translate-x-[2px] hover:bg-white hover:text-[#18345e] hover:shadow-[0_16px_30px_rgba(17,42,78,0.08)]';
    $menuLinkActive = 'bg-white text-[#12284c] shadow-[0_18px_34px_rgba(17,42,78,0.1)]';
    $menuIconBase = 'menu-icon grid h-9 w-9 shrink-0 place-items-center rounded-[0.8rem] text-[0.85rem] transition duration-200';
    $menuIconInactive = 'bg-[#e6edf7] text-[#6f84a1] group-hover:bg-[#e1efff] group-hover:text-[#1a73e8]';
    $menuIconActive = 'bg-[#e1efff] text-[#1a73e8]';
    $primaryItems = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => route('dashboard')],
        ['key' => 'analytics', 'label' => 'Analytics', 'icon' => 'fa-chart-line', 'route' => route('analytics.index')],
    ];

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

<aside class="sidebar border-b border-[#e1e9f3] bg-[linear-gradient(180deg,#f9fbff_0%,#f2f6fd_100%)] px-3 py-4 xl:sticky xl:top-0 xl:h-screen xl:overflow-y-auto xl:border-r xl:border-b-0">
    <a
        class="brand block rounded-[1rem] border border-[#dfe8f4] bg-white px-4 py-4 text-[1.15rem] font-extrabold leading-[1.12] tracking-[-0.03em] text-[#313a46] shadow-[0_18px_32px_rgba(15,35,64,0.08)] transition duration-200 hover:-translate-y-px hover:shadow-[0_22px_38px_rgba(15,35,64,0.1)] sm:max-w-[16rem]"
        href="{{ route('home') }}"
        target="_blank"
        rel="noopener noreferrer"
    >
        Starlink Kenya
    </a>

    <div class="menu-block mt-7">
        @foreach ($primaryItems as $item)
            <a class="{{ $menuLinkBase }} {{ $active === $item['key'] ? $menuLinkActive : $menuLinkInactive }}" href="{{ $item['route'] }}">
                <span class="{{ $menuIconBase }} {{ $active === $item['key'] ? $menuIconActive : $menuIconInactive }}"><i class="fa-solid {{ $item['icon'] }}"></i></span>{{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <div class="menu-block mt-6">
        <h3 class="menu-title mb-2 px-1 text-[0.7rem] font-bold uppercase tracking-[0.2em] text-[#97a8bf]">Content Management</h3>
        @foreach ($menuItems as $item)
            <a class="{{ $menuLinkBase }} {{ $active === $item['key'] ? $menuLinkActive : $menuLinkInactive }}" href="{{ $item['route'] }}">
                <span class="{{ $menuIconBase }} {{ $active === $item['key'] ? $menuIconActive : $menuIconInactive }}"><i class="fa-solid {{ $item['icon'] }}"></i></span>{{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <div class="menu-block mt-6">
        <h3 class="menu-title mb-2 px-1 text-[0.7rem] font-bold uppercase tracking-[0.2em] text-[#97a8bf]">Admin Panel</h3>
        @foreach ($adminItems as $item)
            <a class="{{ $menuLinkBase }} {{ $active === $item['key'] ? $menuLinkActive : $menuLinkInactive }}" href="{{ $item['route'] }}">
                <span class="{{ $menuIconBase }} {{ $active === $item['key'] ? $menuIconActive : $menuIconInactive }}"><i class="fa-solid {{ $item['icon'] }}"></i></span>{{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <div class="menu-block mt-6">
        <h3 class="menu-title mb-2 px-1 text-[0.7rem] font-bold uppercase tracking-[0.2em] text-[#97a8bf]">Account</h3>
        <a class="{{ $menuLinkBase }} {{ $active === 'profile' ? $menuLinkActive : $menuLinkInactive }}" href="{{ route('admin.section', ['section' => 'profile']) }}">
            <span class="{{ $menuIconBase }} {{ $active === 'profile' ? $menuIconActive : $menuIconInactive }}"><i class="fa-solid fa-user-pen"></i></span>Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="menu-logout group mt-2 flex w-full items-center gap-3 rounded-[0.95rem] px-2.5 py-2.5 text-left text-[0.95rem] font-semibold leading-[1.25] text-[#365378] transition duration-200 hover:translate-x-[2px] hover:bg-white hover:text-[#18345e] hover:shadow-[0_12px_22px_rgba(17,42,78,0.08)]" type="submit">
                <span class="menu-icon grid h-9 w-9 shrink-0 place-items-center rounded-[0.8rem] bg-[#e6edf7] text-[0.85rem] text-[#6f84a1] transition duration-200 group-hover:bg-[#e1efff] group-hover:text-[#1a73e8]"><i class="fa-solid fa-right-from-bracket"></i></span>
                Logout
            </button>
        </form>
    </div>
</aside>

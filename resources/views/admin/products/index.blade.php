@extends('admin.layout', [
    'title' => 'Products',
    'heading' => 'Products',
    'subheading' => 'Manage and view all products available in the system',
    'activeSection' => $activeSection ?? 'products',
])

@section('admin_content')
    <section class="content-card products-index-card">
        <div class="toolbar products-toolbar">
            <div>
                <p class="admin-panel-title">Product List</p>
                <p class="admin-panel-copy">Manage products, prices, and listing details.</p>
            </div>

            <div class="products-toolbar-actions">
                <form class="search-form products-search-form" method="GET" action="{{ route('products.index') }}">
                    <input class="field-input products-search-input" type="text" name="search" value="{{ $search }}" placeholder="Search by product name...">
                    <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                </form>
                <a class="btn" href="{{ route('products.create') }}"><i class="fa-solid fa-plus"></i> Add Product</a>
            </div>
        </div>

        @if (session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrap products-table-wrap">
            <table class="table products-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price (KES)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $index }}</td>
                            <td>
                                @if ($product->image_path)
                                    <img class="thumb products-thumb" src="{{ route('media.show', ['path' => $product->image_path]) }}" alt="{{ $product->name }}">
                                @else
                                    <img class="thumb products-thumb" src="https://images.unsplash.com/photo-1614728263952-84ea256f9679?auto=format&fit=crop&w=320&q=80" alt="{{ $product->name }}">
                                @endif
                            </td>
                            <td>
                                <div class="products-name-wrap">
                                    <p class="products-name">{{ $product->name }}</p>
                                    <p class="products-meta">Slug: {{ $product->slug ?: '-' }}</p>
                                    <div class="products-badges">
                                        <span class="products-badge">{{ $product->category?->name ?: 'Uncategorized' }}</span>
                                        <span class="products-badge {{ $product->google_merchant ? 'is-enabled' : 'is-disabled' }}">
                                            Merchant {{ $product->google_merchant ? 'On' : 'Off' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="products-price">{{ number_format((float) $product->price, 2) }}</td>
                            <td>
                                <div class="action-group products-action-group">
                                    <a class="chip products-chip view" href="{{ route('shop.product.show', ['productSlug' => $product->slug ?: $product->id]) }}" target="_blank" rel="noopener"><i class="fa-solid fa-eye"></i> Preview</a>
                                    <a class="chip products-chip edit" href="{{ route('products.edit', $product) }}"><i class="fa-solid fa-pen-to-square"></i> Update</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="chip products-chip delete" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">{{ $products->onEachSide(1)->links() }}</div>
    </section>
@endsection

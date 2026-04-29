@extends('admin.layout', [
    'title' => 'Products',
    'heading' => 'Products',
    'subheading' => 'Manage and view all products available in the system',
    'activeSection' => $activeSection ?? 'products',
])

@section('admin_content')
    <section class="content-card">
        <div class="toolbar">
            <strong style="font-size:34px;color:#1d3152;">Product List</strong>
            <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                <form class="search-form" method="GET" action="{{ route('products.index') }}">
                    <input class="field-input" style="width:280px;" type="text" name="search" value="{{ $search }}" placeholder="Search by product name...">
                    <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                </form>
                <a class="btn" href="{{ route('products.create') }}"><i class="fa-solid fa-plus"></i> Add Product</a>
            </div>
        </div>

        @if (session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Price (KES)</th>
                        <th>Google Merchant</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $index }}</td>
                            <td>
                                @if ($product->image_path)
                                    <img class="thumb" src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}">
                                @else
                                    <img class="thumb" src="https://images.unsplash.com/photo-1614728263952-84ea256f9679?auto=format&fit=crop&w=320&q=80" alt="{{ $product->name }}">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->slug ?: '-' }}</td>
                            <td>{{ number_format((float) $product->price, 2) }}</td>
                            <td>{{ $product->google_merchant ? 'Yes' : 'No' }}</td>
                            <td>{{ $product->category?->name ?: '-' }}</td>
                            <td>
                                <div class="action-group">
                                    <a class="chip edit" href="{{ route('products.edit', $product) }}"><i class="fa-solid fa-pen-to-square"></i> Update</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="chip delete" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">{{ $products->onEachSide(1)->links() }}</div>
    </section>
@endsection

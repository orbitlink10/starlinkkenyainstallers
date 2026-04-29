@extends('admin.layout', [
    'title' => 'Add Product',
    'heading' => 'Add Product',
    'subheading' => 'Fill in the product details below to add a new item',
    'activeSection' => $activeSection ?? 'products',
])

@section('admin_content')
    <section class="content-card">
        @if ($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif

        <form class="form-grid" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="field-label" for="name">Product Name</label>
                <input class="field-input" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter product name" required>
            </div>

            <div>
                <label class="field-label" for="price">Price (KES)</label>
                <input class="field-input" id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" placeholder="Enter product price" required>
            </div>

            <div>
                <label class="field-label" for="marked_price">Marked Price (KES)</label>
                <input class="field-input" id="marked_price" name="marked_price" type="number" step="0.01" min="0" value="{{ old('marked_price') }}" placeholder="Enter marked price">
            </div>

            <div>
                <label class="field-label" for="quantity">Quantity</label>
                <input class="field-input" id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity') }}" placeholder="Enter product quantity">
            </div>

            <div>
                <label class="field-label" for="category_id">Category</label>
                <select class="field-select" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="field-label" for="sub_category_id">Subcategory</label>
                <select class="field-select" id="sub_category_id" name="sub_category_id">
                    <option value="">Select Subcategory</option>
                    @foreach ($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}" @selected((string) old('sub_category_id') === (string) $subCategory->id)>{{ $subCategory->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="field-label" for="meta_description">Meta Description</label>
                <textarea class="field-textarea" id="meta_description" name="meta_description">{{ old('meta_description') }}</textarea>
            </div>

            <div>
                <label class="field-label" for="description">Description</label>
                <textarea class="field-textarea js-editor" id="description" name="description" style="min-height:260px;">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="field-label" for="image">Product Image</label>
                <input class="field-file" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                <input id="google_merchant" name="google_merchant" type="checkbox" value="1" @checked(old('google_merchant'))>
                <label for="google_merchant">Enable Google Merchant</label>
            </div>

            <div>
                <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Product</button>
                <a class="btn-outline" href="{{ route('products.index') }}">Back to list</a>
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.js-editor',
            height: 260,
            menubar: 'file edit view insert format tools table',
            plugins: 'lists link image table code fullscreen media',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image media | code fullscreen'
        });
    </script>
@endsection


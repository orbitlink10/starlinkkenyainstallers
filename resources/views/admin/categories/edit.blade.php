@extends('admin.layout', [
    'title' => 'Edit Category',
    'heading' => 'Edit Category',
    'subheading' => '',
    'activeSection' => $activeSection ?? 'categories',
])

@section('admin_content')
    <section class="content-card">
        <div class="section-bar">Update Category</div>

        @if ($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif

        <form class="form-grid" method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label class="field-label" for="name">Name *</label>
                <input class="field-input" id="name" name="name" type="text" value="{{ old('name', $category->name) }}" placeholder="Enter category name" required>
            </div>

            <div>
                <label class="field-label" for="meta_description">Meta description</label>
                <textarea class="field-textarea" id="meta_description" name="meta_description">{{ old('meta_description', $category->meta_description) }}</textarea>
            </div>

            <div>
                <label class="field-label" for="description">Description (Optional)</label>
                <textarea class="field-textarea js-editor" id="description" name="description" style="min-height:260px;">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="field-label" for="photo">Photo</label>
                <input class="field-file" id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp">
            </div>

            @if ($category->photo_path)
                <div>
                    <label class="field-label">Current Photo</label>
                    <img class="thumb" src="{{ route('media.show', ['path' => $category->photo_path]) }}" alt="{{ $category->name }}">
                </div>
            @endif

            <div>
                <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Update Category</button>
                <a class="btn-outline" href="{{ route('categories.index') }}">Back to list</a>
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


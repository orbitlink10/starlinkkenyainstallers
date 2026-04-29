@extends('admin.layout', [
    'title' => 'Create Category',
    'heading' => 'Create Category',
    'subheading' => '',
    'activeSection' => $activeSection ?? 'categories',
])

@section('admin_content')
    <section class="content-card">
        <div class="section-bar">Create New Category</div>

        @if ($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif

        <form class="form-grid" method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="field-label" for="name">Name *</label>
                <input class="field-input" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter category name" required>
            </div>

            <div>
                <label class="field-label" for="meta_description">Meta description</label>
                <textarea class="field-textarea" id="meta_description" name="meta_description">{{ old('meta_description') }}</textarea>
            </div>

            <div>
                <label class="field-label" for="description">Description (Optional)</label>
                <textarea class="field-textarea js-editor" id="description" name="description" style="min-height:260px;">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="field-label" for="photo">Photo</label>
                <input class="field-file" id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div>
                <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Category</button>
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


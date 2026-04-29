@extends('admin.layout', [
    'title' => 'Edit Page',
    'heading' => 'Manage Pages',
    'subheading' => '',
    'activeSection' => $activeSection ?? 'pages',
])

@section('admin_content')
    <section class="content-card">
        <div class="section-bar">Update Post</div>

        @if ($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif

        <form class="form-grid" method="POST" action="{{ route('pages.update', $page) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label class="field-label" for="meta_title">Meta Title</label>
                <input class="field-input" id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Enter Meta Title">
            </div>

            <div>
                <label class="field-label" for="meta_description">Meta Description</label>
                <textarea class="field-textarea" id="meta_description" name="meta_description" placeholder="Enter Meta Description">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>

            <div>
                <label class="field-label" for="page_title">Page Title</label>
                <input class="field-input" id="page_title" name="page_title" type="text" value="{{ old('page_title', $page->page_title) }}" placeholder="Enter Keyword Title" required>
            </div>

            <div>
                <label class="field-label" for="image_alt_text">Image Alt Text</label>
                <input class="field-input" id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text', $page->image_alt_text) }}" placeholder="Enter Image Alt Text">
            </div>

            <div>
                <label class="field-label" for="heading_2">Heading 2</label>
                <input class="field-input" id="heading_2" name="heading_2" type="text" value="{{ old('heading_2', $page->heading_2) }}" placeholder="Enter Heading 2">
            </div>

            <div>
                <label class="field-label" for="type">Type</label>
                <select class="field-select" id="type" name="type" required>
                    <option value="Post" @selected(old('type', $page->type) === 'Post')>Post</option>
                    <option value="Page" @selected(old('type', $page->type) === 'Page')>Page</option>
                </select>
            </div>

            <div>
                <label class="field-label" for="image">Image</label>
                <input class="field-file" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
                @if ($page->image_path)
                    <img class="thumb" style="margin-top:10px;" src="{{ asset('storage/'.$page->image_path) }}" alt="{{ $page->image_alt_text ?: $page->page_title }}">
                @endif
            </div>

            <div>
                <label class="field-label" for="page_description">Page Description</label>
                <textarea class="field-textarea js-editor" id="page_description" name="page_description" style="min-height:280px;">{{ old('page_description', $page->page_description) }}</textarea>
            </div>

            <div>
                <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Update Page</button>
                <a class="btn-outline" href="{{ route('pages.index') }}">Back to list</a>
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.js-editor',
            height: 300,
            menubar: 'file edit view insert format tools table',
            plugins: 'lists link image table code fullscreen media',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image media | code fullscreen'
        });
    </script>
@endsection

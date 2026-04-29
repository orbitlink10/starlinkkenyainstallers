@extends('admin.layout', [
    'title' => 'Pages',
    'heading' => 'Pages',
    'subheading' => 'Manage site pages and published content.',
    'activeSection' => $activeSection ?? 'pages',
])

@section('admin_content')
    <section class="content-card">
        <div class="toolbar">
            <strong style="font-size:34px;color:#1d3152;">Post List</strong>
            <a class="btn" href="{{ route('pages.create') }}"><i class="fa-solid fa-plus"></i> Add Page</a>
        </div>

        @if (session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        <div class="toolbar" style="border-bottom:none;padding-top:14px;">
            <form class="search-form" method="GET" action="{{ route('pages.index') }}">
                <input class="field-input" style="width:280px;" type="text" name="search" value="{{ $search }}" placeholder="Search by title...">
                <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Alt Text</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $index => $page)
                        <tr>
                            <td>{{ $pages->firstItem() + $index }}</td>
                            <td>
                                @if ($page->image_path)
                                    <img class="thumb" src="{{ asset('storage/'.$page->image_path) }}" alt="{{ $page->image_alt_text ?: $page->page_title }}">
                                @else
                                    <img class="thumb" src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=300&q=80" alt="{{ $page->page_title }}">
                                @endif
                            </td>
                            <td>{{ $page->page_title }}</td>
                            <td>{{ $page->image_alt_text ?: '-' }}</td>
                            <td>{{ $page->type }}</td>
                            <td>
                                <div class="action-group">
                                    <a class="chip view" href="{{ route('pages.preview', $page) }}" target="_blank" rel="noopener"><i class="fa-solid fa-eye"></i> Preview</a>
                                    <a class="chip edit" href="{{ route('pages.edit', $page) }}"><i class="fa-solid fa-pen-to-square"></i> Update</a>
                                    <form method="POST" action="{{ route('pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="chip delete" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">{{ $pages->onEachSide(1)->links() }}</div>
    </section>
@endsection

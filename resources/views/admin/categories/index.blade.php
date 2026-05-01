@extends('admin.layout', [
    'title' => 'Categories',
    'heading' => 'Categories',
    'subheading' => '',
    'activeSection' => $activeSection ?? 'categories',
])

@section('admin_content')
    <section class="content-card">
        <div class="toolbar">
            <div>
                <p class="admin-panel-title">Category List</p>
                <p class="admin-panel-copy">Manage categories, descriptions, and cover images.</p>
            </div>
            <a class="btn" href="{{ route('categories.create') }}"><i class="fa-solid fa-plus"></i> Create New Category</a>
        </div>

        @if (session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        <div class="toolbar border-t border-[#e6edf6] pt-3.5">
            <form class="search-form" method="GET" action="{{ route('categories.index') }}">
                <input class="field-input w-[16rem] max-w-full" type="text" name="search" value="{{ $search }}" placeholder="Search by category name...">
                <button class="btn-outline" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if ($category->photo_path)
                                    <img class="thumb" src="{{ route('media.show', ['path' => $category->photo_path]) }}" alt="{{ $category->name }}">
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    <a class="chip view" href="{{ route('categories.edit', $category) }}">Show</a>
                                    <a class="chip edit" href="{{ route('categories.edit', $category) }}"><i class="fa-solid fa-pen-to-square"></i> Update</a>
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="chip delete" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">{{ $categories->onEachSide(1)->links() }}</div>
    </section>
@endsection

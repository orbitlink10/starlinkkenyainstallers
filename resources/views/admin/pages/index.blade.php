@extends('admin.layout', [
    'title' => 'Pages',
    'heading' => 'Pages',
    'subheading' => 'Manage site pages and published content.',
    'activeSection' => $activeSection ?? 'pages',
])

@section('admin_content')
    @php
        $showingFrom = $pages->firstItem() ?? 0;
        $showingTo = $pages->lastItem() ?? 0;
    @endphp

    <section class="content-card">
        <div class="toolbar">
            <div>
                <p class="text-[1.65rem] font-extrabold tracking-[-0.04em] text-[#10213e] sm:text-[1.8rem]">Post List</p>
                <p class="mt-1.5 text-[0.93rem] text-[#6b7f9b]">Manage published pages, preview changes, and update content.</p>
            </div>
            <a class="btn" href="{{ route('pages.create') }}"><i class="fa-solid fa-plus"></i> Add Page</a>
        </div>

        @if (session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="flash-error">{{ session('error') }}</div>
        @endif

        <form id="pages-bulk-form" method="POST" action="{{ route('pages.bulk-action') }}">
            @csrf
            @if ($search !== '')
                <input type="hidden" name="search" value="{{ $search }}">
            @endif
        </form>

        <div class="toolbar border-t border-[#e6edf6]">
            <div class="flex flex-wrap items-center gap-3">
                <select class="field-select w-[190px] max-w-full" form="pages-bulk-form" name="bulk_action" aria-label="Bulk actions">
                    <option value="">Bulk actions</option>
                    <option value="delete">Delete selected</option>
                </select>
                <button class="btn" form="pages-bulk-form" type="submit">Apply</button>
                <span class="text-[0.82rem] font-semibold text-[#7287a4]">
                    Showing {{ number_format($showingFrom) }}-{{ number_format($showingTo) }} of {{ number_format($pages->total()) }} pages
                </span>
            </div>

            <form class="search-form" method="GET" action="{{ route('pages.index') }}">
                <div class="relative w-full max-w-[280px]">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[0.88rem] text-[#7d91ad]"></i>
                    <input class="field-input w-full pl-10" type="text" name="search" value="{{ $search }}" placeholder="Search by title...">
                </div>
                <button class="btn-outline" type="submit">Search</button>
            </form>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-[64px]">
                            <label class="sr-only" for="select-all-pages">Select all pages</label>
                            <input id="select-all-pages" class="h-[18px] w-[18px] rounded border border-[#cdd9e8] accent-[#176fe5] focus:ring-2 focus:ring-[#176fe5]/20" type="checkbox">
                        </th>
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
                            <td>
                                <label class="sr-only" for="page-select-{{ $page->id }}">Select {{ $page->page_title }}</label>
                                <input
                                    id="page-select-{{ $page->id }}"
                                    class="js-page-checkbox h-[18px] w-[18px] rounded border border-[#cdd9e8] accent-[#176fe5] focus:ring-2 focus:ring-[#176fe5]/20"
                                    form="pages-bulk-form"
                                    type="checkbox"
                                    name="selected_pages[]"
                                    value="{{ $page->id }}"
                                >
                            </td>
                            <td>{{ $pages->firstItem() + $index }}</td>
                            <td>
                                @if ($page->image_path)
                                    <img class="thumb" src="{{ asset('storage/'.$page->image_path) }}" alt="{{ $page->image_alt_text ?: $page->page_title }}">
                                @else
                                    <img class="thumb" src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=300&q=80" alt="{{ $page->page_title }}">
                                @endif
                            </td>
                            <td>
                                <div class="max-w-[16rem]">
                                    <p class="text-[0.98rem] font-extrabold tracking-[-0.02em] text-[#12284c]">{{ $page->page_title }}</p>
                                    <p class="mt-1.5 text-[0.77rem] text-[#7387a3]">Slug: {{ $page->slug }}</p>
                                </div>
                            </td>
                            <td class="max-w-[15rem] text-[0.92rem]">{{ $page->image_alt_text ?: '-' }}</td>
                            <td>
                                <span class="inline-flex rounded-full bg-[#eef5ff] px-3 py-1.5 text-[0.76rem] font-extrabold text-[#315b95]">{{ $page->type }}</span>
                            </td>
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
                            <td colspan="7" class="py-10 text-center text-[0.95rem] font-semibold text-[#6e809a]">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">{{ $pages->onEachSide(1)->links() }}</div>
    </section>

    <script>
        (() => {
            const masterCheckbox = document.getElementById('select-all-pages');
            const rowCheckboxes = Array.from(document.querySelectorAll('.js-page-checkbox'));

            if (!masterCheckbox || rowCheckboxes.length === 0) {
                return;
            }

            const syncMasterCheckbox = () => {
                const checkedCount = rowCheckboxes.filter((checkbox) => checkbox.checked).length;
                masterCheckbox.checked = checkedCount === rowCheckboxes.length;
                masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
            };

            masterCheckbox.addEventListener('change', () => {
                rowCheckboxes.forEach((checkbox) => {
                    checkbox.checked = masterCheckbox.checked;
                });
                masterCheckbox.indeterminate = false;
            });

            rowCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', syncMasterCheckbox);
            });
        })();
    </script>
@endsection

@extends('layouts.app', ['title' => $page->meta_title ?: $page->page_title.' | Preview'])

@section('content')
    @include('pages.partials.published-page', [
        'page' => $page,
        'backUrl' => route('pages.index'),
        'backLabel' => 'Back to posts',
        'showPreviewBadge' => true,
    ])
@endsection

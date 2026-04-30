@extends('layouts.app', ['title' => $page->meta_title ?: $page->page_title.' | Starlink Kenya'])

@section('content')
    @include('pages.partials.published-page', [
        'page' => $page,
        'backUrl' => route('home'),
        'backLabel' => 'Back',
        'showPreviewBadge' => false,
    ])
@endsection

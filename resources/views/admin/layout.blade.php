@extends('layouts.app', ['title' => $title ?? 'Admin'])

@section('content')
    <div class="admin-shell min-h-screen bg-[radial-gradient(circle_at_top_right,rgba(196,220,248,0.52)_0%,transparent_28%),linear-gradient(180deg,#f9fbff_0%,#f3f7fc_100%)] text-[#10213e] xl:grid xl:grid-cols-[19.5rem_minmax(0,1fr)]">
        @include('dashboard.partials.sidebar', ['activeSection' => $activeSection ?? 'dashboard'])

        <main class="px-4 py-5 sm:px-5 sm:py-6 xl:px-6 xl:py-7">
            <div class="mx-auto w-full max-w-[92rem]">
                <h1 class="admin-shell-title">{{ $heading }}</h1>
                @if (!empty($subheading))
                    <p class="admin-shell-copy">{{ $subheading }}</p>
                @endif
                @yield('admin_content')
            </div>
        </main>
    </div>
@endsection

@extends('layouts.app', ['title' => $title ?? 'Admin'])

@section('content')
    <div class="admin-shell min-h-screen bg-[radial-gradient(circle_at_top_right,rgba(196,220,248,0.52)_0%,transparent_28%),linear-gradient(180deg,#f9fbff_0%,#f3f7fc_100%)] text-[#10213e] xl:grid xl:grid-cols-[23.5rem_minmax(0,1fr)]">
        @include('dashboard.partials.sidebar', ['activeSection' => $activeSection ?? 'dashboard'])

        <main class="px-4 py-5 sm:px-5 sm:py-6 xl:px-7 xl:py-8">
            <div class="mx-auto w-full max-w-[96rem]">
                <h1 class="text-[2.25rem] font-extrabold leading-[0.98] tracking-[-0.045em] text-[#10213e] sm:text-[2.75rem] xl:text-[3.45rem]">{{ $heading }}</h1>
                @if (!empty($subheading))
                    <p class="mt-2 max-w-[42rem] text-[0.98rem] leading-[1.45] text-[#647c9a] sm:text-[1.08rem] xl:text-[1.25rem]">{{ $subheading }}</p>
                @endif
                @yield('admin_content')
            </div>
        </main>
    </div>
@endsection

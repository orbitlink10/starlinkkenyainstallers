@php
    $pageTitle = trim((string) $page->page_title);
    $pageLabel = trim((string) ($page->heading_2 ?: ($page->type === 'Post' ? 'Published Post' : 'Published Page')));
    $contentHtml = trim((string) $page->page_description);
    $summary = (string) \Illuminate\Support\Str::of(strip_tags($contentHtml))->squish()->limit(190, '...');
    $summary = $summary !== '' ? $summary : 'Get reliable Starlink guidance, product support, and practical installation help tailored for customers in Kenya.';
    $heroImage = $page->image_path
        ? asset('storage/'.$page->image_path)
        : 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1200&q=80';
    $backUrl = $backUrl ?? route('home');
    $backLabel = $backLabel ?? 'Back';
    $showPreviewBadge = $showPreviewBadge ?? false;
    $shopUrl = route('home').'#packages';
    $expertUrl = 'tel:+254700123456';
    $imageAlt = $page->image_alt_text ?: $pageTitle;
@endphp

<main class="mx-auto w-full max-w-[1440px] px-5 py-8 sm:px-7 lg:px-10 lg:py-12">
    @if ($showPreviewBadge)
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#caddfb] bg-[#eaf2ff] px-4 py-2 text-sm font-extrabold text-[#2f69c7]">
                <i class="fa-solid fa-eye"></i> Page Preview
            </span>
            <a
                class="inline-flex items-center gap-2 rounded-full border border-[#d7e3f0] bg-white px-5 py-3 text-sm font-bold text-[#345577] transition hover:-translate-y-[1px] hover:text-[#16345d]"
                href="{{ $backUrl }}"
            >
                <i class="fa-solid fa-arrow-left"></i> {{ $backLabel }}
            </a>
        </div>
    @endif

    <section class="grid gap-8 rounded-[2.5rem] border border-white/80 bg-white/90 p-8 shadow-[0_32px_80px_rgba(15,35,64,0.10)] backdrop-blur md:p-10 lg:grid-cols-[1.02fr_0.98fr] lg:items-center lg:gap-12 lg:p-12">
        <div class="flex flex-col justify-center">
            <span class="mb-4 inline-flex w-fit rounded-full bg-[#dbe9ff] px-4 py-2.5 text-[0.82rem] font-extrabold text-[#2467c7] sm:text-[0.92rem]">
                {{ $pageLabel }}
            </span>

            <h1 class="max-w-[16ch] text-[1.95rem] font-extrabold leading-[1.05] tracking-[-0.05em] text-[#111d31] sm:text-[2.35rem] lg:text-[2.95rem]">
                {{ $pageTitle }}
            </h1>

            <p class="mt-4 max-w-[33ch] text-[0.96rem] leading-[1.72] text-[#677d96] sm:text-[1.06rem]">
                {{ $summary }}
            </p>

            <div class="mt-7 flex flex-wrap gap-4">
                <a
                    class="inline-flex items-center justify-center rounded-full bg-[#1f2730] px-6 py-3.5 text-[1rem] font-extrabold text-white shadow-[0_18px_36px_rgba(28,34,44,0.18)] transition hover:-translate-y-[1px] hover:bg-[#151b22] sm:text-[1.08rem]"
                    href="{{ $shopUrl }}"
                >
                    Shop Now
                </a>
                <a
                    class="inline-flex items-center justify-center rounded-full bg-[#222933] px-6 py-3.5 text-[1rem] font-extrabold text-white shadow-[0_18px_36px_rgba(28,34,44,0.18)] transition hover:-translate-y-[1px] hover:bg-[#171d23] sm:text-[1.08rem]"
                    href="{{ $expertUrl }}"
                >
                    Talk to an Expert
                </a>
            </div>
        </div>

        <div class="self-center lg:flex lg:justify-end">
            <div class="mx-auto w-full max-w-[30rem] overflow-hidden rounded-[1.75rem] bg-white shadow-[0_24px_64px_rgba(15,35,64,0.16)]">
                <img class="h-[220px] w-full object-cover sm:h-[260px] lg:h-[300px]" src="{{ $heroImage }}" alt="{{ $imageAlt }}">
            </div>
        </div>
    </section>

    <section class="mt-10 rounded-[2.5rem] border border-[#e4ebf5] bg-white shadow-[0_28px_70px_rgba(15,35,64,0.08)]">
        <div class="relative overflow-hidden rounded-t-[2.5rem] border-b border-[#ebf1f8] bg-white px-6 py-7 sm:px-8 lg:px-12 lg:py-10">
            <div class="absolute inset-y-0 left-0 w-2 bg-gradient-to-b from-[#ff8b1b] via-[#ff9b2f] to-[#ffd29e]"></div>

            <div class="flex flex-wrap items-start justify-between gap-5 pl-4 sm:pl-6">
                <div class="max-w-[58rem]">
                    <p class="text-[1rem] font-extrabold text-[#0f4b8e] sm:text-[1.08rem]">{{ $pageLabel }}</p>
                    <h2 class="mt-3 text-[1.7rem] font-extrabold leading-[1.14] tracking-[-0.045em] text-[#0d1c3d] sm:text-[2rem] lg:text-[2.45rem]">
                        {{ $pageTitle }}
                    </h2>
                </div>

                <a
                    class="inline-flex items-center justify-center rounded-full border border-[#d8e3f1] bg-white px-5 py-3 text-[0.95rem] font-bold text-[#ff8b1b] transition hover:-translate-y-[1px] hover:border-[#ffc381] hover:text-[#e47c0f]"
                    href="{{ $backUrl }}"
                >
                    {{ $backLabel }}
                </a>
            </div>
        </div>

        <div class="px-6 py-7 sm:px-8 lg:px-12 lg:py-10">
            @if ($page->image_path)
                <img
                    class="mb-8 w-full max-w-[42rem] rounded-[1.5rem] object-cover shadow-[0_20px_50px_rgba(15,35,64,0.12)]"
                    src="{{ $heroImage }}"
                    alt="{{ $imageAlt }}"
                >
            @endif

            @if ($contentHtml !== '')
                <div class="published-page-content max-w-[68rem]">
                    {!! $contentHtml !!}
                </div>
            @else
                <div class="published-page-content max-w-[52rem]">
                    <p>Content for this page will appear here once it has been added in the page editor.</p>
                </div>
            @endif
        </div>
    </section>
</main>

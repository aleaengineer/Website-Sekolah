@extends('layouts.app')

@section('title', 'Pengumuman — '.($settings['school.name'] ?? ''))

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Informasi Resmi</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Pengumuman</h1>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4">
        @forelse ($announcements as $announcement)
            <article class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-7">
                <div class="flex flex-wrap items-center gap-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">{{ $announcement->published_at?->translatedFormat('d F Y') }}</p>
                    @if ($announcement->is_pinned)
                        <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-extrabold text-amber-800">Penting</span>
                    @endif
                </div>
                <h2 class="mt-2 font-serif text-xl font-bold text-navy-900">{{ $announcement->title }}</h2>
                <div class="rich-text mt-2 text-sm leading-relaxed text-slate-600">{!! $announcement->content !!}</div>
            </article>
        @empty
            <p class="rounded-3xl bg-white p-10 text-center text-slate-500">Belum ada pengumuman.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $announcements->links() }}
    </div>
</section>
@endsection

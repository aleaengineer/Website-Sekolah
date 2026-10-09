@extends('layouts.app')

@section('title', 'Berita — '.($settings['school.name'] ?? ''))

@section('description', 'Kumpulan berita dan kabar terbaru SMP Negeri Satu Atap I Sidamulih.')

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Kabar Terkini</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Berita Sekolah</h1>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <form action="{{ route('news.index') }}" method="GET" class="mb-6 flex flex-col gap-3 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita…"
               class="grow rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @if (request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <button type="submit" class="rounded-2xl bg-navy-900 px-7 py-3 text-sm font-bold text-white transition hover:bg-navy-800">Cari</button>
    </form>

    @if ($categories->isNotEmpty())
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('news.index', ['q' => request('q')]) }}"
               class="rounded-full px-4 py-1.5 text-xs font-bold transition {{ $activeCategory ? 'bg-slate-200 text-slate-600 hover:bg-slate-300' : 'bg-navy-900 text-white' }}">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ route('news.index', ['q' => request('q'), 'kategori' => $category->slug]) }}"
                   class="rounded-full px-4 py-1.5 text-xs font-bold transition {{ $activeCategory === $category->slug ? 'bg-navy-900 text-white' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">{{ $category->name }}</a>
            @endforeach
        </div>
    @endif

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($news as $index => $article)
            <a href="{{ route('news.show', $article) }}" class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg">
                <x-cover class="h-44" :title="$article->title" :seed="$index" :image="$article->cover_image" />
                <div class="p-5">
                    <div class="flex items-center gap-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">{{ $article->published_at?->translatedFormat('d M Y') }}</p>
                        @if ($article->category)
                            <span class="rounded-full bg-sky-100 px-2.5 py-0.5 text-[11px] font-bold text-sky-800">{{ $article->category->name }}</span>
                        @endif
                    </div>
                    <h2 class="mt-2 line-clamp-2 font-extrabold text-navy-900 group-hover:text-emerald-700">{{ $article->title }}</h2>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $article->excerpt }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-3 rounded-3xl bg-white p-8 text-center text-slate-500">Belum ada berita yang dipublikasikan.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $news->links() }}
    </div>
</section>
@endsection

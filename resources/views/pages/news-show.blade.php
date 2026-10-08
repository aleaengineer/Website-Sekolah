@extends('layouts.app')

@section('title', $article->title.' — '.($settings['school.name'] ?? ''))

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('news.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-800">&larr; Kembali ke Berita</a>

    <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">{{ $article->published_at?->translatedFormat('l, d F Y') }}</p>
    <h1 class="mt-2 font-serif text-3xl font-bold leading-tight text-navy-950 sm:text-4xl">{{ $article->title }}</h1>

    <x-cover class="mt-8 h-64 rounded-3xl sm:h-80" :title="$article->title" :seed="$article->id" :image="$article->cover_image" />

    <article class="rich-text mt-8 max-w-none text-slate-700">
        {!! $article->body !!}
    </article>

    @if ($related->isNotEmpty())
        <div class="mt-14">
            <h2 class="font-serif text-2xl font-bold text-navy-900">Berita Terkait</h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-3">
                @foreach ($related as $index => $item)
                    <a href="{{ route('news.show', $item) }}" class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:shadow-lg">
                        <x-cover class="h-32" :title="$item->title" :seed="$index + 3" />
                        <div class="p-4">
                            <h3 class="line-clamp-2 text-sm font-extrabold text-navy-900 group-hover:text-emerald-700">{{ $item->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection

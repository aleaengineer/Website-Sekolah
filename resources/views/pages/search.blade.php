@extends('layouts.app')

@section('title', 'Hasil pencarian')

@section('meta-robots', 'noindex, follow')

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Pencarian</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Hasil untuk &ldquo;{{ $q }}&rdquo;</h1>
        <form action="{{ route('search') }}" method="GET" class="mt-6 flex max-w-xl flex-col gap-3 sm:flex-row">
            <input type="text" name="q" value="{{ $q }}" required minlength="2" maxlength="100" placeholder="Cari berita, agenda, guru..."
                   class="grow rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-300 outline-none transition focus:border-amber-300">
            <button type="submit" class="rounded-2xl bg-amber-400 px-6 py-3 text-sm font-extrabold text-navy-950 transition hover:brightness-105">Cari</button>
        </form>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    @php($total = collect($groups)->sum(fn ($group) => $group['items']->count()))

    @if ($total === 0)
        <p class="rounded-3xl bg-white p-10 text-center text-sm text-slate-500 ring-1 ring-slate-100">Tidak ditemukan hasil untuk &ldquo;{{ $q }}&rdquo;. Coba kata kunci lain, mis. PPDB, upacara, atau nama guru.</p>
    @else
        <p class="text-sm text-slate-500">{{ $total }} hasil ditemukan.</p>
        @foreach ($groups as $group)
            @if ($group['items']->isNotEmpty())
                <h2 class="mb-4 mt-10 font-serif text-xl font-bold text-navy-900">{{ $group['label'] }} ({{ $group['items']->count() }})</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($group['items'] as $item)
                        <a href="{{ $item['url'] }}" class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:shadow-md">
                            <p class="font-extrabold text-navy-900">{{ $item['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $item['desc'] }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    @endif
</section>
@endsection

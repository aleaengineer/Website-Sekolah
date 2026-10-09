@extends('layouts.app')

@section('title', $agenda->title.' — '.($settings['school.name'] ?? ''))

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
        <a href="{{ route('agendas.index') }}" class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300 hover:underline">&larr; Semua Agenda</a>
        <h1 class="mt-3 font-serif text-3xl font-bold text-white sm:text-4xl">{{ $agenda->title }}</h1>
        <p class="mt-3 text-sm text-slate-200">{{ $agenda->start_at->translatedFormat('l, d F Y H:i') }}
            @if ($agenda->end_at)
                &ndash; {{ $agenda->end_at->translatedFormat('l, d F Y H:i') }}
            @endif
            @if ($agenda->location)
                &bull; {{ $agenda->location }}
            @endif
        </p>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <article class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
        @if ($agenda->cover_image)
            <img src="{{ asset('storage/'.$agenda->cover_image) }}" alt="{{ $agenda->title }}" class="mb-6 w-full rounded-2xl object-cover ring-1 ring-slate-100">
        @endif
        <div class="rich-text max-w-none text-slate-700">
            {!! $agenda->description !!}
        </div>
    </article>

    @if ($others->isNotEmpty())
        <h2 class="mt-12 font-serif text-xl font-bold text-navy-900">Agenda Lainnya</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            @foreach ($others as $other)
                <a href="{{ route('agendas.show', $other) }}" class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:shadow-md">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">{{ $other->start_at->translatedFormat('d M Y') }}</p>
                    <p class="mt-2 text-sm font-extrabold text-navy-900">{{ \Illuminate\Support\Str::limit($other->title, 60) }}</p>
                </a>
            @endforeach
        </div>
    @endif
</section>
@endsection

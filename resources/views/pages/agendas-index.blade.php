@extends('layouts.app')

@section('title', 'Agenda — '.($settings['school.name'] ?? ''))

@section('description', 'Jadwal agenda dan kegiatan SMP Negeri Satu Atap I Sidamulih.')

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Kegiatan Sekolah</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Agenda</h1>
        <p class="mt-2 text-slate-200">Jadwal kegiatan, upacara, dan acara sekolah yang akan datang.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    @if ($upcoming->isNotEmpty())
        <x-section-heading eyebrow="Segera Hadir" align="left">Agenda Terdekat</x-section-heading>
        <div class="mt-8 grid gap-5 md:grid-cols-2">
            @foreach ($upcoming as $agenda)
                <a href="{{ route('agendas.show', $agenda) }}" class="group rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition hover:shadow-md">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">{{ $agenda->start_at->translatedFormat('l, d F Y H:i') }}</p>
                    <h2 class="mt-2 text-lg font-extrabold text-navy-900 group-hover:underline">{{ $agenda->title }}</h2>
                    @if ($agenda->location)
                        <p class="mt-1 text-sm text-slate-500">{{ $agenda->location }}</p>
                    @endif
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($agenda->description), 140) }}</p>
                </a>
            @endforeach
        </div>
    @endif

    <div class="mt-14">
        <x-section-heading eyebrow="Semua" align="left">Arsip Agenda</x-section-heading>
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            @forelse ($agendas as $agenda)
                <a href="{{ route('agendas.show', $agenda) }}" class="group overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:shadow-md">
                    @if ($agenda->cover_image)
                        <img src="{{ asset('storage/'.$agenda->cover_image) }}" alt="{{ $agenda->title }}" class="h-44 w-full object-cover" loading="lazy" decoding="async">
                    @endif
                    <div class="p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">{{ $agenda->start_at->translatedFormat('d M Y') }}</p>
                        <h3 class="mt-2 font-extrabold text-navy-900 group-hover:underline">{{ \Illuminate\Support\Str::limit($agenda->title, 60) }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($agenda->description), 100) }}</p>
                    </div>
                </a>
            @empty
                <p class="rounded-3xl bg-white p-8 text-center text-sm text-slate-500 ring-1 ring-slate-100 md:col-span-3">Belum ada agenda yang ditayangkan.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $agendas->links() }}</div>
    </div>
</section>
@endsection

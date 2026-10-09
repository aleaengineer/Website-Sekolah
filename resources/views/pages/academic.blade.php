@extends('layouts.app')

@section('title', 'Akademik — '.($settings['school.name'] ?? ''))

@section('description', 'Informasi akademik SMP Negeri Satu Atap I Sidamulih: kurikulum, ekstrakurikuler, kalender pendidikan, dan dokumen unduhan.')

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Akademik</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Kurikulum & Kegiatan</h1>
        <p class="mt-2 text-slate-200">Pembelajaran intrakurikuler, kokurikuler, dan ekstrakurikuler sesuai Kurikulum Merdeka.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Kurikulum Merdeka">Struktur Pembelajaran</x-section-heading>
    <div class="mt-10 grid gap-5 md:grid-cols-3">
        <div class="rounded-3xl bg-gradient-to-br from-navy-800 to-navy-950 p-7 text-white shadow">
            <p class="text-xs font-bold uppercase tracking-widest text-amber-300">Fase D</p>
            <h3 class="mt-2 text-xl font-extrabold">Kelas VII – IX</h3>
            <p class="mt-3 text-sm leading-relaxed text-slate-200">Pembelajaran tatap muka mata pelajaran umum dan muatan lokal dengan pendekatan berdiferensiasi.</p>
        </div>
        <div class="rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-700 p-7 text-white shadow">
            <p class="text-xs font-bold uppercase tracking-widest text-amber-200">Kokurikuler</p>
            <h3 class="mt-2 text-xl font-extrabold">Projek P5</h3>
            <p class="mt-3 text-sm leading-relaxed text-emerald-50">Projek Penguatan Profil Pelajar Pancasila: gaya hidup berkelanjutan, kearifan lokal, dan kewirausahaan.</p>
        </div>
        <div class="rounded-3xl bg-gradient-to-br from-amber-400 to-orange-500 p-7 text-navy-950 shadow">
            <p class="text-xs font-bold uppercase tracking-widest">Ekstrakurikuler</p>
            <h3 class="mt-2 text-xl font-extrabold">Bakat & Minat</h3>
            <p class="mt-3 text-sm font-medium leading-relaxed">Pengembangan diri melalui Pramuka, olahraga, seni, dan kepemudaan setiap pekan.</p>
        </div>
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-heading eyebrow="Jadwal Mingguan">Ekstrakurikuler</x-section-heading>
        <div class="mt-10 overflow-hidden rounded-3xl ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="bg-navy-900 text-white">
                            <th class="px-6 py-4 font-bold">Kegiatan</th>
                            <th class="px-6 py-4 font-bold">Jadwal</th>
                            <th class="px-6 py-4 font-bold">Pembina</th>
                            <th class="px-6 py-4 font-bold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($extracurriculars as $ekskul)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-extrabold text-navy-900">{{ $ekskul->name }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $ekskul->schedule ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $ekskul->coach_name ?? 'Segera dilengkapi' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ \Illuminate\Support\Str::limit($ekskul->description, 80) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Data ekstrakurikuler segera dilengkapi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Agenda">Kalender Pendidikan</x-section-heading>
    <div class="mx-auto mt-10 grid max-w-4xl gap-4">
        @forelse ($calendars as $entry)
            <div class="flex gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <div class="w-2 shrink-0 rounded-full bg-gradient-to-b from-emerald-500 to-teal-600" aria-hidden="true"></div>
                <div class="grow">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-extrabold text-navy-900">{{ $entry->title }}</h3>
                        <span class="rounded-full bg-slate-100 px-3 py-0.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">{{ $calendarCategories[$entry->category] ?? $entry->category }}</span>
                    </div>
                    <p class="mt-1 text-xs font-bold text-emerald-700">{{ $entry->start_date->translatedFormat('d M Y') }}@if ($entry->end_date) &ndash; {{ $entry->end_date->translatedFormat('d M Y') }}@endif</p>
                    @if ($entry->description)
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $entry->description }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="rounded-3xl bg-white p-8 text-center text-sm text-slate-500 ring-1 ring-slate-100">Kalender pendidikan segera dilengkapi.</p>
        @endforelse
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-heading eyebrow="Kegiatan">Agenda Terdekat</x-section-heading>
        <div class="mt-10 grid gap-5 md:grid-cols-2">
            @forelse ($upcomingAgendas as $agenda)
                <a href="{{ route('agendas.show', $agenda) }}" class="group rounded-3xl bg-slate-50 p-6 ring-1 ring-slate-100 transition hover:bg-white hover:shadow-md">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">{{ $agenda->start_at->translatedFormat('l, d F Y H:i') }}</p>
                    <h3 class="mt-2 font-extrabold text-navy-900 group-hover:underline">{{ $agenda->title }}</h3>
                    @if ($agenda->location)
                        <p class="mt-1 text-sm text-slate-500">{{ $agenda->location }}</p>
                    @endif
                </a>
            @empty
                <p class="rounded-3xl bg-slate-50 p-8 text-center text-sm text-slate-500 ring-1 ring-slate-100 md:col-span-2">Belum ada agenda terdekat. <a href="{{ route('agendas.index') }}" class="font-bold text-emerald-700 hover:underline">Lihat arsip agenda</a>.</p>
            @endforelse
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('agendas.index') }}" class="inline-block rounded-2xl bg-navy-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-navy-800">Lihat Semua Agenda</a>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Unduhan">Dokumen Akademik</x-section-heading>
    <div class="mx-auto mt-10 grid max-w-4xl gap-4">
        @forelse ($documents as $document)
            <div class="flex items-center gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-navy-800 to-emerald-700 text-lg font-extrabold text-white">D</span>
                <div class="min-w-0 grow">
                    <p class="font-extrabold text-navy-900">{{ $document->title }}</p>
                    <p class="mt-0.5 text-xs font-bold uppercase tracking-wider text-slate-400">{{ $documentCategories[$document->category] ?? $document->category }}</p>
                    @if ($document->description)
                        <p class="mt-1 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($document->description, 120) }}</p>
                    @endif
                </div>
                <a href="{{ asset('storage/'.$document->file) }}" target="_blank" class="shrink-0 rounded-2xl bg-emerald-600 px-5 py-2.5 text-xs font-extrabold text-white transition hover:brightness-110">Unduh</a>
            </div>
        @empty
            <p class="rounded-3xl bg-white p-8 text-center text-sm text-slate-500 ring-1 ring-slate-100">Belum ada dokumen yang bisa diunduh.</p>
        @endforelse
    </div>
</section>
@endsection

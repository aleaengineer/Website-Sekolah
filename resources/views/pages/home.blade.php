@extends('layouts.app')

@section('title', ($settings['school.name'] ?? 'SMP Negeri Satu Atap I Sidamulih').' — Beranda')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-navy-950">
    <div class="absolute inset-0 bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800" aria-hidden="true"></div>
    <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-amber-400/20 blur-3xl" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -left-16 h-96 w-96 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-2 lg:px-8 lg:py-24">
        <div>
            <div class="flex flex-wrap gap-2">
                <span class="rounded-full bg-amber-400 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-navy-950">Akreditasi {{ $settings['school.accreditation'] ?? 'B' }}</span>
                <span class="rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white">NPSN {{ $settings['school.npsn'] ?? '20253310' }}</span>
                <span class="rounded-full bg-emerald-500 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-white">Sekolah Negeri</span>
            </div>
            <h1 class="mt-6 font-serif text-4xl font-bold leading-tight text-white sm:text-5xl">
                {{ $settings['school.name'] ?? 'SMP Negeri Satu Atap I Sidamulih' }}
            </h1>
            <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-200 sm:text-lg">
                {{ $settings['school.description'] ?? '' }}
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('ppdb.index') }}" class="rounded-2xl bg-gradient-to-r from-amber-400 to-orange-500 px-7 py-3.5 text-sm font-extrabold text-navy-950 shadow-lg transition hover:brightness-105">
                    Daftar PPDB {{ $settings['ppdb.year'] ?? '' }}
                </a>
                <a href="{{ route('profile') }}" class="rounded-2xl border border-white/30 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-white/10">
                    Kenali Sekolah Kami
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-3xl bg-white/10 p-6 text-center backdrop-blur">
                <p class="text-4xl font-extrabold text-amber-300">{{ $settings['school.students_count'] ?? '150' }}+</p>
                <p class="mt-1 text-sm font-semibold text-slate-200">Peserta Didik</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-6 text-center backdrop-blur">
                <p class="text-4xl font-extrabold text-emerald-300">{{ $teacherCount }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-200">Guru & Tendik</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-6 text-center backdrop-blur">
                <p class="text-4xl font-extrabold text-sky-300">{{ $newsCount }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-200">Kabar Sekolah</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-6 text-center backdrop-blur">
                <p class="text-4xl font-extrabold text-rose-300">{{ $extracurriculars->count() }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-200">Ekstrakurikuler</p>
            </div>
        </div>
    </div>

    <svg class="relative block w-full text-slate-50" viewBox="0 0 1440 70" fill="currentColor" preserveAspectRatio="none" aria-hidden="true"><path d="M0 70h1440V35C1200 60 960 70 720 55 480 40 240 15 0 35v35Z"/></svg>
</section>

{{-- Sambutan --}}
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid items-center gap-10 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <div class="rounded-3xl bg-gradient-to-br from-navy-800 to-emerald-700 p-10 text-center text-white shadow-xl">
                <x-logo class="mx-auto h-20 w-20" />
                <p class="mt-4 text-lg font-extrabold">{{ $settings['school.principal_name'] ?? '' }}</p>
                <p class="text-sm text-slate-200">Kepala Sekolah</p>
            </div>
        </div>
        <div class="lg:col-span-3">
            <x-section-heading eyebrow="Sambutan" align="left">Kepala Sekolah</x-section-heading>
            <p class="mt-5 leading-relaxed text-slate-600">{{ $settings['school.principal_greeting'] ?? '' }}</p>
            <a href="{{ route('profile') }}" class="mt-6 inline-block text-sm font-bold text-emerald-700 hover:text-emerald-800">Baca profil lengkap &rarr;</a>
        </div>
    </div>
</section>

{{-- Keunggulan --}}
<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-heading eyebrow="Mengapa Kami">Keunggulan Sekolah</x-section-heading>
        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $features = [
                    ['Gratis & Negeri', 'Sekolah negeri dengan biaya pendidikan terjangkau dan transparan bagi masyarakat Sidamulih.', 'from-emerald-500 to-teal-600'],
                    ['Kurikulum Merdeka', 'Pembelajaran aktif, kreatif, dan berpusat pada peserta didik sesuai Kurikulum Merdeka.', 'from-navy-700 to-navy-900'],
                    ['Karakter & Disiplin', 'Pembinaan akhlak, kedisiplinan, dan gotong royong dalam setiap kegiatan sekolah.', 'from-amber-400 to-orange-500'],
                    ['Akses Dekat', 'Lokasi strategis di Kalijati, mudah dijangkau warga Sidamulih dan sekitarnya.', 'from-sky-500 to-indigo-600'],
                ];
            @endphp
            @foreach ($features as $feature)
                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $feature[2] }}" aria-hidden="true"></div>
                    <h3 class="mt-4 text-lg font-extrabold text-navy-900">{{ $feature[0] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $feature[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Ekstrakurikuler --}}
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Bakat & Minat">Ekstrakurikuler</x-section-heading>
    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($extracurriculars as $index => $ekskul)
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition hover:shadow-lg">
                <x-cover class="h-36" :title="$ekskul->name" :seed="$index" />
                <div class="p-5">
                    <h3 class="text-lg font-extrabold text-navy-900">{{ $ekskul->name }}</h3>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $ekskul->schedule }}</p>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $ekskul->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('academic') }}" class="inline-block rounded-2xl bg-navy-900 px-7 py-3 text-sm font-bold text-white transition hover:bg-navy-800">Lihat Semua Kegiatan</a>
    </div>
</section>

{{-- Berita --}}
<section class="bg-navy-950 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-heading eyebrow="Kabar Terkini"><span class="text-white">Berita Sekolah</span></x-section-heading>
        <div class="mt-10 grid gap-5 md:grid-cols-3">
            @forelse ($latestNews as $index => $article)
                <a href="{{ route('news.show', $article) }}" class="group overflow-hidden rounded-3xl bg-white shadow transition hover:-translate-y-1 hover:shadow-xl">
                    <x-cover class="h-44" :title="$article->title" :seed="$index + 1" :image="$article->cover_image" />
                    <div class="p-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">{{ $article->published_at?->translatedFormat('d M Y') }}</p>
                        <h3 class="mt-2 line-clamp-2 font-extrabold text-navy-900 group-hover:text-emerald-700">{{ $article->title }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $article->excerpt }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-3 rounded-3xl bg-white/10 p-8 text-center text-slate-200">Belum ada berita. Nantikan kabar terbaru dari kami.</p>
            @endforelse
        </div>
        <div class="mt-8 text-center">
            <a href="{{ route('news.index') }}" class="inline-block rounded-2xl border border-white/30 px-7 py-3 text-sm font-bold text-white transition hover:bg-white/10">Semua Berita</a>
        </div>
    </div>
</section>

{{-- Pengumuman --}}
@if ($announcements->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Informasi Resmi">Pengumuman</x-section-heading>
    <div class="mx-auto mt-10 grid max-w-4xl gap-4">
        @foreach ($announcements as $announcement)
            <div class="flex gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-navy-950" aria-hidden="true">
                    <span class="text-lg font-extrabold leading-none">{{ $announcement->published_at?->format('d') }}</span>
                    <span class="text-[10px] font-bold uppercase">{{ $announcement->published_at?->translatedFormat('M') }}</span>
                </div>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-extrabold text-navy-900">{{ $announcement->title }}</h3>
                        @if ($announcement->is_pinned)
                            <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-extrabold text-amber-800">Penting</span>
                        @endif
                    </div>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $announcement->content }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('announcements') }}" class="inline-block rounded-2xl bg-navy-900 px-7 py-3 text-sm font-bold text-white transition hover:bg-navy-800">Semua Pengumuman</a>
    </div>
</section>
@endif

{{-- Galeri --}}
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Momen">Galeri Kegiatan</x-section-heading>
    <div class="mt-10 grid grid-cols-2 gap-4 lg:grid-cols-3">
        @foreach ($galleries as $index => $item)
            <x-cover class="h-44 rounded-3xl sm:h-56" :title="$item->title" :seed="$index + 2" :image="$item->image" />
        @endforeach
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('gallery') }}" class="inline-block rounded-2xl bg-navy-900 px-7 py-3 text-sm font-bold text-white transition hover:bg-navy-800">Buka Galeri</a>
    </div>
</section>

{{-- CTA PPDB --}}
<section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-400 via-orange-500 to-rose-500 p-10 text-center shadow-xl sm:p-14">
        <h2 class="font-serif text-3xl font-bold text-navy-950 sm:text-4xl">PPDB Tahun Ajaran {{ $settings['ppdb.year'] ?? '' }} Telah Dibuka</h2>
        <p class="mx-auto mt-3 max-w-2xl font-medium text-navy-900">Bergabunglah bersama kami. Kuota tersedia {{ $settings['ppdb.quota'] ?? '' }} kursi untuk peserta didik baru.</p>
        <a href="{{ route('ppdb.index') }}" class="mt-7 inline-block rounded-2xl bg-navy-950 px-8 py-3.5 text-sm font-extrabold text-white shadow transition hover:bg-navy-900">Daftar Sekarang</a>
    </div>
</section>
@endsection

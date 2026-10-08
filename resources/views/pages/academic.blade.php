@extends('layouts.app')

@section('title', 'Akademik — '.($settings['school.name'] ?? ''))

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
        @php
            $agenda = [
                ['Semester Ganjil', 'Juli – Desember: Masa pengenalan lingkungan sekolah, pembelajaran efektif, asesmen sumatif tengah & akhir semester.', 'from-navy-700 to-navy-900'],
                ['Semester Genap', 'Januari – Juni: Pembelajaran efektif, ujian sekolah, dan kenaikan kelas.', 'from-emerald-500 to-teal-600'],
                ['Kegiatan Tahunan', 'Pesantren kilat, class meeting, perkemahan, dan wisuda kelulusan. Jadwal detail menyusul melalui pengumuman sekolah.', 'from-amber-400 to-orange-500'],
            ];
        @endphp
        @foreach ($agenda as $item)
            <div class="flex gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <div class="w-2 shrink-0 rounded-full bg-gradient-to-b {{ $item[2] }}" aria-hidden="true"></div>
                <div>
                    <h3 class="font-extrabold text-navy-900">{{ $item[0] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $item[1] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection

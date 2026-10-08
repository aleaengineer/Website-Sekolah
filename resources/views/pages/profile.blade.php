@extends('layouts.app')

@section('title', 'Profil — '.($settings['school.name'] ?? ''))

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Profil Sekolah</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">{{ $settings['school.name'] ?? '' }}</h1>
        <p class="mt-2 text-slate-200">{{ $settings['school.address'] ?? '' }}, Kec. {{ $settings['school.district'] ?? '' }}, Kab. {{ $settings['school.regency'] ?? '' }}</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $facts = [
                ['NPSN', $settings['school.npsn'] ?? '-', 'from-navy-700 to-navy-900'],
                ['Status', ($settings['school.status'] ?? 'Negeri').' / Akreditasi '.($settings['school.accreditation'] ?? 'B'), 'from-emerald-500 to-teal-600'],
                ['Jenjang', 'Sekolah Menengah Pertama (SMP)', 'from-amber-400 to-orange-500'],
                ['Peserta Didik', ($settings['school.students_count'] ?? '150').'+ siswa', 'from-sky-500 to-indigo-600'],
            ];
        @endphp
        @foreach ($facts as $fact)
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <div class="h-2 w-12 rounded-full bg-gradient-to-r {{ $fact[2] }}" aria-hidden="true"></div>
                <p class="mt-3 text-xs font-bold uppercase tracking-widest text-slate-500">{{ $fact[0] }}</p>
                <p class="mt-1 font-extrabold text-navy-900">{{ $fact[1] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-14 grid gap-10 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
            <x-section-heading eyebrow="Tentang Kami" align="left">Sejarah Singkat</x-section-heading>
            <p class="mt-5 whitespace-pre-line leading-relaxed text-slate-600">{{ $settings['school.history'] ?? '' }}</p>
        </div>
        <div class="flex flex-col gap-6">
            <div class="rounded-3xl bg-gradient-to-br from-navy-900 to-emerald-800 p-8 text-white shadow">
                <h2 class="font-serif text-2xl font-bold text-amber-300">Visi</h2>
                <p class="mt-3 leading-relaxed text-slate-100">{{ $settings['school.vision'] ?? '' }}</p>
            </div>
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                <h2 class="font-serif text-2xl font-bold text-navy-900">Misi</h2>
                <p class="mt-3 whitespace-pre-line leading-relaxed text-slate-600">{{ $settings['school.mission'] ?? '' }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-heading eyebrow="SDM">Guru & Tenaga Kependidikan</x-section-heading>
        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($teachers as $index => $teacher)
                <div class="rounded-3xl bg-slate-50 p-6 text-center ring-1 ring-slate-100 transition hover:shadow-lg">
                    @if ($teacher->photo)
                        <img src="{{ asset('storage/'.$teacher->photo) }}" alt="{{ $teacher->name }}" class="mx-auto aspect-[2/3] w-1/2 rounded-2xl object-cover ring-1 ring-slate-200" loading="lazy">
                    @else
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-navy-700 to-emerald-600 text-2xl font-extrabold text-white" aria-hidden="true">
                            {{ strtoupper(mb_substr($teacher->name, 0, 1)) }}
                        </div>
                    @endif
                    <h3 class="mt-4 font-extrabold text-navy-900">{{ $teacher->name }}</h3>
                    <p class="text-sm font-semibold text-emerald-700">{{ $teacher->position }}</p>
                    @if ($teacher->subject)
                        <p class="mt-1 text-xs text-slate-500">{{ $teacher->subject }}</p>
                    @endif
                </div>
            @empty
                <p class="col-span-4 rounded-3xl bg-slate-50 p-8 text-center text-slate-500">Data guru segera dilengkapi.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <x-section-heading eyebrow="Lokasi">Peta Sekolah</x-section-heading>
    <div class="mt-8 overflow-hidden rounded-3xl shadow ring-1 ring-slate-200">
        <iframe title="Peta lokasi sekolah" src="{{ \App\Models\Setting::mapsEmbedUrl() ?? 'https://www.google.com/maps?q=Sidamulih,Pangandaran,Jawa+Barat&output=embed' }}" class="h-96 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
    <p class="mt-4 text-center text-sm text-slate-500">{{ $settings['school.address'] ?? '' }}, {{ $settings['school.village'] ?? '' }}, Kec. {{ $settings['school.district'] ?? '' }}, Kab. {{ $settings['school.regency'] ?? '' }}, {{ $settings['school.province'] ?? '' }}</p>
</section>
@endsection

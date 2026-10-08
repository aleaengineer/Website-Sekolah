@extends('layouts.app')

@section('title', 'Cek Status PPDB — '.($settings['school.name'] ?? ''))

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">PPDB</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Cek Status Pendaftaran</h1>
        <p class="mt-2 text-slate-200">Masukkan nomor pendaftaran yang diterima setelah mengisi formulir.</p>
    </div>
</section>

<section class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
    <form action="{{ route('ppdb.check') }}" method="GET" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8">
        <label for="nomor" class="mb-1.5 block text-sm font-bold text-navy-900">Nomor Pendaftaran</label>
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="nomor" id="nomor" value="{{ old('nomor', request('nomor')) }}" required placeholder="mis. PPDB-2026-0001"
                   class="grow rounded-2xl border border-slate-200 px-4 py-3 font-mono text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            <button type="submit" class="rounded-2xl bg-navy-900 px-8 py-3 text-sm font-extrabold text-white transition hover:bg-navy-800">Cek Status</button>
        </div>
        @error('nomor')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
    </form>

    @if (isset($registration))
        @php
            $statusLabels = ['menunggu' => 'Menunggu Verifikasi', 'diverifikasi' => 'Berkas Diverifikasi', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'];
        @endphp
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="font-mono text-sm font-bold text-slate-500">{{ $registration->registration_number }}</p>
                    <h2 class="mt-1 font-serif text-2xl font-bold text-navy-900">{{ $registration->student_name }}</h2>
                </div>
                <span class="rounded-full px-4 py-1.5 text-sm font-extrabold
                    {{ $registration->status === 'diterima' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $registration->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                    {{ $registration->status === 'diverifikasi' ? 'bg-sky-100 text-sky-800' : '' }}
                    {{ $registration->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : '' }}">
                    {{ $statusLabels[$registration->status] ?? $registration->status }}
                </span>
            </div>

            {{-- Banner ucapan selamat (blok mandiri: hapus blok @if ini untuk rollback) --}}
            @if ($registration->status === 'diterima')
                <div class="mt-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 p-5 text-white shadow">
                    <p class="text-lg font-extrabold">Selamat!</p>
                    <p class="mt-1 text-sm leading-relaxed">Selamat atas diterimanya <span class="font-extrabold">{{ $registration->student_name }}</span> sebagai peserta didik baru SMP Negeri Satu Atap I Sidamulih. Silakan cetak bukti pendaftaran dan lakukan daftar ulang ke sekolah sesuai jadwal.</p>
                </div>
            @endif

            <dl class="mt-6 grid gap-x-8 gap-y-3 text-sm sm:grid-cols-2">
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Asal Sekolah</dt><dd class="mt-0.5 font-semibold text-navy-900">{{ $registration->previous_school }}</dd></div>
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Jalur</dt><dd class="mt-0.5 font-semibold text-navy-900">{{ ucfirst($registration->jalur) }}</dd></div>
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Mendaftar Pada</dt><dd class="mt-0.5 font-semibold text-navy-900">{{ $registration->created_at->translatedFormat('d F Y H:i') }}</dd></div>
            </dl>

            <a href="{{ route('ppdb.print', $registration->registration_number) }}" target="_blank" class="mt-7 inline-block rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-7 py-3 text-sm font-extrabold text-white shadow transition hover:brightness-110">
                Cetak Bukti Pendaftaran
            </a>
        </div>
    @endif
</section>
@endsection

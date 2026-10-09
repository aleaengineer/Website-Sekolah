@extends('layouts.app')

@section('title', 'PPDB — '.($settings['school.name'] ?? ''))

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Penerimaan Peserta Didik Baru</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">PPDB Tahun Ajaran {{ $settings['ppdb.year'] ?? '' }}</h1>
        <div class="mt-4 flex flex-wrap gap-2">
            @if (($settings['ppdb.is_open'] ?? '1') === '1')
                <span class="rounded-full bg-emerald-500 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-white">Pendaftaran Dibuka</span>
            @else
                <span class="rounded-full bg-rose-500 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-white">Pendaftaran Ditutup</span>
            @endif
            <span class="rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white">Kuota {{ $settings['ppdb.quota'] ?? '' }} Kursi</span>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
        <h2 class="font-serif text-2xl font-bold text-navy-900">Informasi Pendaftaran</h2>
        <p class="mt-3 leading-relaxed text-slate-600">{{ $settings['ppdb.info'] ?? '' }}</p>

        <h3 class="mt-8 font-extrabold text-navy-900">Gelombang Pendaftaran</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            @forelse ($waves as $wave)
                <div class="rounded-2xl p-5 ring-1 {{ $openWaves->contains($wave) ? 'bg-emerald-50 ring-emerald-200' : 'bg-slate-50 ring-slate-100' }}">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-extrabold text-navy-900">{{ $wave->name }}</p>
                        @if ($openWaves->contains($wave))
                            <span class="rounded-full bg-emerald-500 px-3 py-1 text-[11px] font-extrabold uppercase tracking-widest text-white">Dibuka</span>
                        @elseif (! $wave->isOpen())
                            <span class="rounded-full bg-slate-300 px-3 py-1 text-[11px] font-extrabold uppercase tracking-widest text-slate-700">Ditutup</span>
                        @else
                            <span class="rounded-full bg-rose-500 px-3 py-1 text-[11px] font-extrabold uppercase tracking-widest text-white">Penuh</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-slate-600">{{ $wave->start_date->translatedFormat('d M Y') }} &ndash; {{ $wave->end_date->translatedFormat('d M Y') }}</p>
                    <p class="mt-1 text-sm font-bold text-navy-900">Sisa kuota {{ max(0, $wave->quota - $wave->registrations_count) }} dari {{ $wave->quota }}</p>
                    @if ($wave->description)
                        <p class="mt-1 text-sm text-slate-600">{{ $wave->description }}</p>
                    @endif
                </div>
            @empty
                <p class="rounded-2xl bg-slate-50 p-5 text-sm text-slate-500 ring-1 ring-slate-100 sm:col-span-2">Gelombang pendaftaran segera diumumkan. Pantau halaman ini dan pengumuman sekolah.</p>
            @endforelse
        </div>

        <h3 class="mt-8 font-extrabold text-navy-900">Alur Pendaftaran</h3>
        <ol class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $steps = [
                    ['Isi Formulir', 'Lengkapi formulir pendaftaran online di bawah ini dengan data yang benar.'],
                    ['Catat Nomor', 'Simpan nomor pendaftaran yang muncul setelah formulir terkirim.'],
                    ['Verifikasi Berkas', 'Datang ke sekolah membawa nomor pendaftaran, fotokopi KK, akta kelahiran, dan rapor/SKHU.'],
                    ['Pengumuman', 'Pantau pengumuman hasil seleksi melalui papan informasi sekolah.'],
                ];
            @endphp
            @foreach ($steps as $i => $step)
                <li class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-100">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-900 text-sm font-extrabold text-white">{{ $i + 1 }}</span>
                    <p class="mt-3 font-extrabold text-navy-900">{{ $step[0] }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $step[1] }}</p>
                </li>
            @endforeach
        </ol>
    </div>

    <div class="mt-10 rounded-3xl bg-gradient-to-r from-navy-900 to-emerald-800 p-8 shadow">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-serif text-xl font-bold text-white">Sudah mendaftar?</h2>
                <p class="mt-1 text-sm text-slate-200">Cek status pendaftaran dan cetak bukti dengan nomor pendaftaran Anda.</p>
            </div>
            <a href="{{ route('ppdb.check') }}" class="shrink-0 rounded-2xl bg-amber-400 px-6 py-3 text-sm font-extrabold text-navy-950 transition hover:brightness-105">Cek Status PPDB</a>
        </div>
    </div>

    <div class="mt-10 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
        <x-section-heading eyebrow="Formulir" align="left">Pendaftaran Online</x-section-heading>

        <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-5 sm:grid-cols-2">
            @csrf

            <div class="sm:col-span-2">
                <label for="student_name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Lengkap Calon Peserta Didik</label>
                <input type="text" name="student_name" id="student_name" value="{{ old('student_name') }}" required
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @error('student_name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="birth_place" class="mb-1.5 block text-sm font-bold text-navy-900">Tempat Lahir</label>
                <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}" required
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @error('birth_place')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="birth_date" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @error('birth_date')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="gender" class="mb-1.5 block text-sm font-bold text-navy-900">Jenis Kelamin</label>
                <select name="gender" id="gender" required
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    <option value="">— Pilih —</option>
                    <option value="L" @selected(old('gender') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender') === 'P')>Perempuan</option>
                </select>
                @error('gender')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="jalur" class="mb-1.5 block text-sm font-bold text-navy-900">Jalur Pendaftaran</label>
                <select name="jalur" id="jalur" required
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    <option value="">— Pilih —</option>
                    @foreach ($jalurList as $value => $label)
                        <option value="{{ $value }}" @selected(old('jalur') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jalur')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="ppdb_wave_id" class="mb-1.5 block text-sm font-bold text-navy-900">Gelombang Pendaftaran</label>
                <select name="ppdb_wave_id" id="ppdb_wave_id" required
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    <option value="">— Pilih gelombang —</option>
                    @foreach ($waves as $wave)
                        <option value="{{ $wave->id }}" @selected(old('ppdb_wave_id') == $wave->id) @disabled(! $openWaves->contains($wave))>
                            {{ $wave->name }} (sisa {{ max(0, $wave->quota - $wave->registrations_count) }})
                        </option>
                    @endforeach
                </select>
                @error('ppdb_wave_id')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="previous_school" class="mb-1.5 block text-sm font-bold text-navy-900">Asal Sekolah (SD/MI)</label>
                <input type="text" name="previous_school" id="previous_school" value="{{ old('previous_school') }}" required
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @error('previous_school')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="parent_name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Orang Tua / Wali</label>
                <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}" required
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @error('parent_name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="parent_phone" class="mb-1.5 block text-sm font-bold text-navy-900">No. HP Orang Tua / Wali</label>
                <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" required placeholder="08xxxxxxxxxx"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @error('parent_phone')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="address" class="mb-1.5 block text-sm font-bold text-navy-900">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="3" required
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('address') }}</textarea>
                @error('address')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <h3 class="font-extrabold text-navy-900">Berkas Pendukung <span class="font-semibold text-slate-400">(opsional, PDF/JPG/PNG maks 4 MB; foto maks 2 MB)</span></h3>
                <p class="mt-1 text-sm text-slate-500">Unggah sekarang untuk mempercepat verifikasi, atau bawa fotokopinya saat verifikasi berkas ke sekolah.</p>
            </div>

            <div>
                <label for="kk_file" class="mb-1.5 block text-sm font-bold text-navy-900">Kartu Keluarga (KK)</label>
                <input type="file" name="kk_file" id="kk_file" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
                @error('kk_file')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="akta_file" class="mb-1.5 block text-sm font-bold text-navy-900">Akta Kelahiran</label>
                <input type="file" name="akta_file" id="akta_file" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
                @error('akta_file')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="rapor_file" class="mb-1.5 block text-sm font-bold text-navy-900">Rapor / SKHU Terakhir</label>
                <input type="file" name="rapor_file" id="rapor_file" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
                @error('rapor_file')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="photo" class="mb-1.5 block text-sm font-bold text-navy-900">Pas Foto</label>
                <input type="file" name="photo" id="photo" accept=".jpg,.jpeg,.png"
                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
                @error('photo')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-4 text-sm font-extrabold text-white shadow transition hover:brightness-110 sm:w-auto">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

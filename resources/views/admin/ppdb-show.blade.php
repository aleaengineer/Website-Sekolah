@extends('admin.layout')

@section('title', 'Detail Pendaftar')
@section('heading', $registration->registration_number)

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 lg:col-span-2">
        <h2 class="font-serif text-xl font-bold text-navy-900">Data Calon Peserta Didik</h2>
        <dl class="mt-5 grid gap-x-8 gap-y-4 sm:grid-cols-2">
            @php
                $rows = [
                    'Nama Lengkap' => $registration->student_name,
                    'Tempat, Tanggal Lahir' => $registration->birth_place.', '.$registration->birth_date->translatedFormat('d F Y'),
                    'Jenis Kelamin' => $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                    'Asal Sekolah' => $registration->previous_school,
                    'Jalur' => ucfirst($registration->jalur),
                    'Gelombang' => $registration->wave?->name ?? '—',
                    'Nama Orang Tua/Wali' => $registration->parent_name,
                    'No. HP Orang Tua/Wali' => $registration->parent_phone,
                    'Email Orang Tua/Wali' => $registration->parent_email ?? '—',
                    'Mendaftar Pada' => $registration->created_at->translatedFormat('d F Y H:i'),
                ];
            @endphp
            @foreach ($rows as $label => $value)
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $label }}</dt>
                    <dd class="mt-1 font-semibold text-navy-900">{{ $value }}</dd>
                </div>
            @endforeach
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</dt>
                <dd class="mt-1 font-semibold text-navy-900">{{ $registration->address }}</dd>
            </div>
        </dl>

        <h2 class="mt-8 font-serif text-xl font-bold text-navy-900">Berkas Pendaftar</h2>
        @php
            $files = [
                'Kartu Keluarga' => ['path' => $registration->kk_file, 'field' => 'kk_verified', 'verified' => $registration->kk_verified],
                'Akta Kelahiran' => ['path' => $registration->akta_file, 'field' => 'akta_verified', 'verified' => $registration->akta_verified],
                'Rapor / SKHU' => ['path' => $registration->rapor_file, 'field' => 'rapor_verified', 'verified' => $registration->rapor_verified],
                'Pas Foto' => ['path' => $registration->photo, 'field' => 'photo_verified', 'verified' => $registration->photo_verified],
            ];
        @endphp
        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach ($files as $label => $file)
                <li class="flex items-center justify-between gap-2 rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-100">
                    <div>
                        <p class="text-sm font-bold text-navy-900">{{ $label }}</p>
                        @if ($file['verified'])
                            <p class="text-xs font-bold text-emerald-700">Terverifikasi</p>
                        @elseif ($file['path'])
                            <p class="text-xs font-bold text-amber-700">Belum verifikasi</p>
                        @else
                            <p class="text-xs text-slate-400">Belum diunggah</p>
                        @endif
                    </div>
                    @if ($file['path'])
                        <a href="{{ asset('storage/'.$file['path']) }}" target="_blank" class="shrink-0 rounded-xl bg-navy-900 px-4 py-2 text-xs font-bold text-white hover:bg-navy-800">Lihat</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <h2 class="font-serif text-xl font-bold text-navy-900">Verifikasi</h2>
        <form action="{{ route('admin.ppdb.update', $registration) }}" method="POST" class="mt-4 flex flex-col gap-3">
            @csrf
            @method('PATCH')
            @foreach ($statuses as $value => $label)
                <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold transition has-checked:border-emerald-500 has-checked:bg-emerald-50">
                    <input type="radio" name="status" value="{{ $value }}" @checked($registration->status === $value) class="h-4 w-4 accent-emerald-600">
                    {{ $label }}
                </label>
            @endforeach
            @error('status')<p class="text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror

            <p class="mt-2 text-sm font-extrabold text-navy-900">Verifikasi per berkas</p>
            @foreach (['kk_verified' => 'KK valid', 'akta_verified' => 'Akta valid', 'rapor_verified' => 'Rapor valid', 'photo_verified' => 'Foto valid'] as $field => $label)
                <label class="flex cursor-pointer items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
                    <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $registration->{$field})) class="h-5 w-5 rounded accent-emerald-600">
                    {{ $label }}
                </label>
            @endforeach

            <label for="verification_note" class="mt-2 text-sm font-extrabold text-navy-900">Catatan untuk pendaftar</label>
            <textarea name="verification_note" id="verification_note" rows="3" placeholder="mis. Akta kurang jelas, mohon bawa aslinya saat daftar ulang."
                      class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('verification_note', $registration->verification_note) }}</textarea>
            @error('verification_note')<p class="text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror

            <button type="submit" class="mt-2 rounded-2xl bg-navy-900 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-navy-800">
                Simpan Verifikasi
            </button>
        </form>
        @if ($waLink)
            <a href="{{ $waLink }}" target="_blank" class="mt-2 block rounded-2xl bg-emerald-600 px-6 py-3 text-center text-sm font-extrabold text-white transition hover:brightness-110">
                Kirim WA ke Orang Tua
            </a>
        @endif
        <a href="{{ route('admin.ppdb.index') }}" class="mt-4 block text-center text-sm font-semibold text-slate-500 hover:text-navy-900">&larr; Kembali ke daftar</a>
    </div>
</div>
@endsection

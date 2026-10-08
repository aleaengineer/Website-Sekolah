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
                    'Nama Orang Tua/Wali' => $registration->parent_name,
                    'No. HP Orang Tua/Wali' => $registration->parent_phone,
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
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <h2 class="font-serif text-xl font-bold text-navy-900">Status</h2>
        <form action="{{ route('admin.ppdb.update', $registration) }}" method="POST" class="mt-4 flex flex-col gap-3">
            @csrf
            @method('PATCH')
            @php
                $options = ['menunggu' => 'Menunggu', 'diverifikasi' => 'Diverifikasi', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'];
            @endphp
            @foreach ($options as $value => $label)
                <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold transition has-checked:border-emerald-500 has-checked:bg-emerald-50">
                    <input type="radio" name="status" value="{{ $value }}" @checked($registration->status === $value) class="h-4 w-4 accent-emerald-600">
                    {{ $label }}
                </label>
            @endforeach
            @error('status')<p class="text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            <button type="submit" class="mt-2 rounded-2xl bg-navy-900 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-navy-800">
                Perbarui Status
            </button>
        </form>
        <a href="{{ route('admin.ppdb.index') }}" class="mt-4 block text-center text-sm font-semibold text-slate-500 hover:text-navy-900">&larr; Kembali ke daftar</a>
    </div>
</div>
@endsection

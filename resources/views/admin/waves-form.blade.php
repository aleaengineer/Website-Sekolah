@extends('admin.layout')

@section('title', isset($wave) ? 'Ubah Gelombang' : 'Tambah Gelombang')
@section('heading', isset($wave) ? 'Ubah Gelombang' : 'Tambah Gelombang')

@section('content')
<form action="{{ isset($wave) ? route('admin.waves.update', $wave) : route('admin.waves.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($wave))
        @method('PUT')
    @endif

    @if (isset($wave))
        <p class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600 ring-1 ring-slate-100">Terisi {{ $wave->registrations_count }} dari {{ $wave->quota }} kuota.</p>
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Gelombang</label>
        <input type="text" name="name" id="name" value="{{ old('name', $wave->name ?? '') }}" required placeholder="Gelombang 1 2026/2027"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Keterangan</label>
        <textarea name="description" id="description" rows="3"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $wave->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <div>
            <label for="start_date" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Buka</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($wave) ? $wave->start_date->format('Y-m-d') : '') }}" required
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('start_date')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="end_date" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Tutup</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($wave) ? $wave->end_date->format('Y-m-d') : '') }}" required
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('end_date')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="quota" class="mb-1.5 block text-sm font-bold text-navy-900">Kuota Kursi</label>
            <input type="number" name="quota" id="quota" value="{{ old('quota', $wave->quota ?? 48) }}" required min="1" max="10000"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('quota')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <label class="flex cursor-pointer items-center gap-2 rounded-2xl bg-slate-50 p-4 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $wave->is_active ?? true)) class="h-5 w-5 rounded accent-emerald-600">
        Gelombang aktif dan bisa dipilih pendaftar
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.waves.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

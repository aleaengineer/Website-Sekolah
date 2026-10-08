@extends('admin.layout')

@section('title', isset($item) && $item->exists ? 'Ubah Ekstrakurikuler' : 'Tambah Ekstrakurikuler')
@section('heading', isset($item) && $item->exists ? 'Ubah Ekstrakurikuler' : 'Tambah Ekstrakurikuler')

@section('content')
<form action="{{ isset($item) && $item->exists ? route('admin.extracurriculars.update', $item) : route('admin.extracurriculars.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 sm:grid-cols-2">
    @csrf
    @if (isset($item) && $item->exists)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Kegiatan</label>
        <input type="text" name="name" id="name" value="{{ old('name', $item->name ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="slug" class="mb-1.5 block text-sm font-bold text-navy-900">Slug <span class="font-normal text-slate-400">(opsional)</span></label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $item->slug ?? '') }}"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('slug')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Deskripsi</label>
        <textarea name="description" id="description" rows="3"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $item->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="coach_name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Pembina</label>
        <input type="text" name="coach_name" id="coach_name" value="{{ old('coach_name', $item->coach_name ?? '') }}"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('coach_name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="schedule" class="mb-1.5 block text-sm font-bold text-navy-900">Jadwal</label>
        <input type="text" name="schedule" id="schedule" value="{{ old('schedule', $item->schedule ?? '') }}" placeholder="Sabtu, 13.00 - 15.00 WIB"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('schedule')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="sort_order" class="mb-1.5 block text-sm font-bold text-navy-900">Urutan Tampil</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="9999" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('sort_order')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-end pb-1">
        <label class="flex cursor-pointer items-center gap-2 text-sm font-bold text-navy-900">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true)) class="h-5 w-5 rounded accent-emerald-600">
            Tampilkan di situs
        </label>
    </div>

    <div class="flex items-center gap-3 sm:col-span-2">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.extracurriculars.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

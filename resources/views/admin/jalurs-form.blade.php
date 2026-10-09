@extends('admin.layout')

@section('title', isset($jalur) ? 'Ubah Jalur' : 'Tambah Jalur')
@section('heading', isset($jalur) ? 'Ubah Jalur' : 'Tambah Jalur')

@section('content')
<form action="{{ isset($jalur) ? route('admin.jalurs.update', $jalur) : route('admin.jalurs.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($jalur))
        @method('PUT')
    @endif

    @if (isset($jalur))
        <p class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600 ring-1 ring-slate-100">Slug <span class="font-mono font-bold">{{ $jalur->slug }}</span> tidak bisa diubah karena dipakai sebagai kode jalur pendaftar. Dipakai {{ $jalur->registrations_count }} pendaftar.</p>
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Jalur</label>
        <input type="text" name="name" id="name" value="{{ old('name', $jalur->name ?? '') }}" required placeholder="mis. Prestasi"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Keterangan</label>
        <textarea name="description" id="description" rows="3"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $jalur->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="sort_order" class="mb-1.5 block text-sm font-bold text-navy-900">Urutan Tampil</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $jalur->sort_order ?? 0) }}" required min="0" max="9999"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 sm:max-w-xs">
        @error('sort_order')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <label class="flex cursor-pointer items-center gap-2 rounded-2xl bg-slate-50 p-4 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $jalur->is_active ?? true)) class="h-5 w-5 rounded accent-emerald-600">
        Jalur aktif dan tampil di formulir pendaftaran
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.jalurs.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

@extends('admin.layout')

@section('title', isset($slide) ? 'Ubah Slide' : 'Tambah Slide')
@section('heading', isset($slide) ? 'Ubah Slide Hero' : 'Tambah Slide Hero')

@section('content')
<form action="{{ isset($slide) ? route('admin.heroes.update', $slide) : route('admin.heroes.store') }}" method="POST" enctype="multipart/form-data"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($slide))
        @method('PUT')
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="eyebrow" class="mb-1.5 block text-sm font-bold text-navy-900">Label Kecil (opsional)</label>
            <input type="text" name="eyebrow" id="eyebrow" value="{{ old('eyebrow', $slide->eyebrow ?? '') }}" placeholder="Penerimaan 2026"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('eyebrow')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="sort_order" class="mb-1.5 block text-sm font-bold text-navy-900">Urutan Tampil</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $slide->sort_order ?? 0) }}" required min="0" max="9999"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('sort_order')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Judul</label>
        <input type="text" name="title" id="title" value="{{ old('title', $slide->title ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="subtitle" class="mb-1.5 block text-sm font-bold text-navy-900">Subjudul</label>
        <textarea name="subtitle" id="subtitle" rows="3"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('subtitle', $slide->subtitle ?? '') }}</textarea>
        @error('subtitle')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="button_text" class="mb-1.5 block text-sm font-bold text-navy-900">Teks Tombol (opsional)</label>
            <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $slide->button_text ?? '') }}" placeholder="Daftar PPDB"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('button_text')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="button_url" class="mb-1.5 block text-sm font-bold text-navy-900">Link Tombol (opsional)</label>
            <input type="text" name="button_url" id="button_url" value="{{ old('button_url', $slide->button_url ?? '') }}" placeholder="/ppdb"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('button_url')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="image" class="mb-1.5 block text-sm font-bold text-navy-900">Gambar Latar (JPG/PNG/WebP, maks 2 MB)</label>
        @if (isset($slide?->image))
            <img src="{{ asset('storage/'.$slide->image) }}" alt="Latar slide" class="mb-3 h-36 w-full rounded-2xl object-cover ring-1 ring-slate-200">
        @endif
        <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp"
               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
        @error('image')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <label class="flex cursor-pointer items-center gap-2 rounded-2xl bg-slate-50 p-4 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slide->is_active ?? true)) class="h-5 w-5 rounded accent-emerald-600">
        Tampilkan di beranda
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.heroes.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

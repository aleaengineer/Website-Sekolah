@extends('admin.layout')

@section('title', isset($gallery) ? 'Ubah Foto' : 'Tambah Foto')
@section('heading', isset($gallery) ? 'Ubah Foto' : 'Tambah Foto')

@section('content')
<form action="{{ isset($gallery) ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 sm:grid-cols-2">
    @csrf
    @if (isset($gallery))
        @method('PUT')
    @endif

    <div class="sm:col-span-2">
        <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Judul Foto</label>
        <input type="text" name="title" id="title" value="{{ old('title', $gallery->title ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="image" class="mb-1.5 block text-sm font-bold text-navy-900">Berkas Foto <span class="font-normal text-slate-400">(maks. 4 MB)</span></label>
        @if (! empty($gallery?->image))
            <img src="{{ asset('storage/'.$gallery->image) }}" alt="Foto" class="mb-3 h-40 w-full rounded-2xl object-cover ring-1 ring-slate-200">
        @endif
        <input type="file" name="image" id="image" accept="image/*"
               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold">
        @error('image')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Keterangan</label>
        <textarea name="description" id="description" rows="2"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $gallery->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="taken_at" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Diambil</label>
        <input type="date" name="taken_at" id="taken_at" value="{{ old('taken_at', isset($gallery?->taken_at) ? $gallery->taken_at->format('Y-m-d') : '') }}"
               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('taken_at')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="sort_order" class="mb-1.5 block text-sm font-bold text-navy-900">Urutan Tampil</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $gallery->sort_order ?? 0) }}" min="0" max="9999" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('sort_order')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 sm:col-span-2">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.galleries.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

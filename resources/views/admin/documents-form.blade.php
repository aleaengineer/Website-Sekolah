@extends('admin.layout')

@section('title', isset($document) ? 'Ubah Dokumen' : 'Unggah Dokumen')
@section('heading', isset($document) ? 'Ubah Dokumen' : 'Unggah Dokumen')

@section('content')
<form action="{{ isset($document) ? route('admin.documents.update', $document) : route('admin.documents.store') }}" method="POST" enctype="multipart/form-data"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($document))
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Judul Dokumen</label>
        <input type="text" name="title" id="title" value="{{ old('title', $document->title ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Keterangan</label>
        <textarea name="description" id="description" rows="3"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $document->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="category" class="mb-1.5 block text-sm font-bold text-navy-900">Kategori</label>
            <select name="category" id="category" required
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $document->category ?? 'umum') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="sort_order" class="mb-1.5 block text-sm font-bold text-navy-900">Urutan Tampil</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $document->sort_order ?? 0) }}" required min="0" max="9999"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('sort_order')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="file" class="mb-1.5 block text-sm font-bold text-navy-900">Berkas {{ isset($document) ? '(kosongkan bila tidak diganti)' : '' }}</label>
        @if (isset($document))
            <p class="mb-2 text-sm text-slate-600">Berkas saat ini: <a href="{{ asset('storage/'.$document->file) }}" target="_blank" class="font-bold text-emerald-700 hover:underline">{{ $document->file }}</a></p>
        @endif
        <input type="file" name="file" id="file" {{ isset($document) ? '' : 'required' }}
               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
        <p class="mt-1 text-xs text-slate-400">PDF/DOC/XLS/PPT/ZIP/gambar, maks 10 MB.</p>
        @error('file')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <label class="flex cursor-pointer items-center gap-2 rounded-2xl bg-slate-50 p-4 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $document->is_published ?? false)) class="h-5 w-5 rounded accent-emerald-600">
        Tayangkan dan bisa diunduh publik
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.documents.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

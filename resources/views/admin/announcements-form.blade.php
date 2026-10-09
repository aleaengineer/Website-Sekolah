@extends('admin.layout')

@section('title', isset($announcement) ? 'Ubah Pengumuman' : 'Tulis Pengumuman')
@section('heading', isset($announcement) ? 'Ubah Pengumuman' : 'Tulis Pengumuman')

@section('content')
<form action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($announcement))
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Judul</label>
        <input type="text" name="title" id="title" value="{{ old('title', $announcement->title ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="content" class="mb-1.5 block text-sm font-bold text-navy-900">Isi Pengumuman</label>
        <textarea name="content" id="content" rows="6"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('content', $announcement->content ?? '') }}</textarea>
        @error('content')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="published_at" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Terbit</label>
            <input type="datetime-local" name="published_at" id="published_at"
                   value="{{ old('published_at', isset($announcement?->published_at) ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}"
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            <p class="mt-1 text-xs text-slate-400">Kosongkan untuk terbit saat ini bila ditayangkan.</p>
            @error('published_at')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col justify-center gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100">
            <label class="flex cursor-pointer items-center gap-2 text-sm font-bold text-navy-900">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $announcement->is_published ?? false)) class="h-5 w-5 rounded accent-emerald-600">
                Tayangkan
            </label>
            <label class="flex cursor-pointer items-center gap-2 text-sm font-bold text-navy-900">
                <input type="checkbox" name="is_pinned" value="1" @checked(old('is_pinned', $announcement->is_pinned ?? false)) class="h-5 w-5 rounded accent-amber-500">
                Sematkan di paling atas
            </label>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.announcements.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

@push('admin-styles')
<style>
    .ck-editor__editable_inline { min-height: 220px; }
</style>
@endpush

@push('admin-scripts')
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
        })
        .catch(function (error) { console.error(error); });
</script>
@endpush

@extends('admin.layout')

@section('title', isset($article) ? 'Ubah Berita' : 'Tulis Berita')
@section('heading', isset($article) ? 'Ubah Berita' : 'Tulis Berita')

@section('content')
<form action="{{ isset($article) ? route('admin.news.update', $article) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
      class="grid gap-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 lg:grid-cols-3">
    @csrf
    @if (isset($article))
        @method('PUT')
    @endif

    <div class="flex flex-col gap-5 lg:col-span-2">
        <div>
            <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Judul</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}" required
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="mb-1.5 block text-sm font-bold text-navy-900">Slug <span class="font-normal text-slate-400">(opsional, otomatis dari judul bila kosong)</span></label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug ?? '') }}"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('slug')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="category_id" class="mb-1.5 block text-sm font-bold text-navy-900">Kategori</label>
            <select name="category_id" id="category_id"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                <option value="">— Tanpa kategori —</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $article->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="excerpt" class="mb-1.5 block text-sm font-bold text-navy-900">Ringkasan</label>
            <textarea name="excerpt" id="excerpt" rows="2"
                      class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
            @error('excerpt')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="body" class="mb-1.5 block text-sm font-bold text-navy-900">Isi Berita</label>
            <textarea name="body" id="body" rows="10"
                      class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('body', $article->body ?? '') }}</textarea>
            @error('body')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex flex-col gap-5">
        <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-100">
            <label class="flex cursor-pointer items-center justify-between gap-3">
                <span class="text-sm font-bold text-navy-900">Tayangkan</span>
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published ?? false)) class="h-5 w-5 rounded accent-emerald-600">
            </label>
            <div class="mt-4">
                <label for="published_at" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Terbit</label>
                <input type="datetime-local" name="published_at" id="published_at"
                       value="{{ old('published_at', isset($article?->published_at) ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                <p class="mt-1 text-xs text-slate-400">Kosongkan untuk terbit saat ini bila ditayangkan.</p>
                @error('published_at')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="cover_image" class="mb-1.5 block text-sm font-bold text-navy-900">Foto Sampul <span class="font-normal text-slate-400">(maks. 2 MB)</span></label>
            @if (! empty($article?->cover_image))
                <img src="{{ asset('storage/'.$article->cover_image) }}" alt="Sampul" class="mb-3 h-36 w-full rounded-2xl object-cover ring-1 ring-slate-200">
            @endif
            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold">
            @error('cover_image')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan Berita
        </button>
        <a href="{{ route('admin.news.index') }}" class="text-center text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

@push('admin-scripts')
@include('admin.partials.editor', ['field' => 'body'])
@endpush

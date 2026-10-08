@extends('admin.layout')

@section('title', isset($teacher) ? 'Ubah Guru' : 'Tambah Guru')
@section('heading', isset($teacher) ? 'Ubah Guru' : 'Tambah Guru')

@section('content')
<form action="{{ isset($teacher) ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 sm:grid-cols-2">
    @csrf
    @if (isset($teacher))
        @method('PUT')
    @endif

    <div class="sm:col-span-2">
        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Lengkap</label>
        <input type="text" name="name" id="name" value="{{ old('name', $teacher->name ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="position" class="mb-1.5 block text-sm font-bold text-navy-900">Jabatan</label>
        <input type="text" name="position" id="position" value="{{ old('position', $teacher->position ?? 'Guru') }}" required placeholder="Kepala Sekolah / Guru / Tata Usaha"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('position')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="subject" class="mb-1.5 block text-sm font-bold text-navy-900">Mata Pelajaran</label>
        <input type="text" name="subject" id="subject" value="{{ old('subject', $teacher->subject ?? '') }}" placeholder="Matematika"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('subject')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="sort_order" class="mb-1.5 block text-sm font-bold text-navy-900">Urutan Tampil</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $teacher->sort_order ?? 0) }}" min="0" max="9999" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('sort_order')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-end pb-1">
        <label class="flex cursor-pointer items-center gap-2 text-sm font-bold text-navy-900">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $teacher->is_active ?? true)) class="h-5 w-5 rounded accent-emerald-600">
            Tampilkan di situs
        </label>
    </div>

    <div class="sm:col-span-2">
        <label for="photo" class="mb-1.5 block text-sm font-bold text-navy-900">Foto <span class="font-normal text-slate-400">(maks. 2 MB)</span></label>
        @if (! empty($teacher?->photo))
            <img src="{{ asset('storage/'.$teacher->photo) }}" alt="Foto" class="mb-3 h-24 w-24 rounded-full object-cover ring-2 ring-emerald-200">
        @endif
        <input type="file" name="photo" id="photo" accept="image/*"
               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold">
        @error('photo')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 sm:col-span-2">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.teachers.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

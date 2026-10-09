@extends('admin.layout')

@section('title', isset($calendar) ? 'Ubah Kalender' : 'Tambah Kalender')
@section('heading', isset($calendar) ? 'Ubah Kalender' : 'Tambah Kalender')

@section('content')
<form action="{{ isset($calendar) ? route('admin.calendars.update', $calendar) : route('admin.calendars.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($calendar))
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Kegiatan</label>
        <input type="text" name="title" id="title" value="{{ old('title', $calendar->title ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Keterangan</label>
        <textarea name="description" id="description" rows="4"
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $calendar->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <div>
            <label for="start_date" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', isset($calendar) ? $calendar->start_date->format('Y-m-d') : '') }}" required
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('start_date')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="end_date" class="mb-1.5 block text-sm font-bold text-navy-900">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', isset($calendar?->end_date) ? $calendar->end_date->format('Y-m-d') : '') }}"
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('end_date')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="category" class="mb-1.5 block text-sm font-bold text-navy-900">Kategori</label>
            <select name="category" id="category" required
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $calendar->category ?? 'kegiatan') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <label class="flex cursor-pointer items-center gap-2 rounded-2xl bg-slate-50 p-4 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $calendar->is_published ?? false)) class="h-5 w-5 rounded accent-emerald-600">
        Tayangkan di situs
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.calendars.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

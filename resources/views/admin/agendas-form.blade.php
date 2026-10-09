@extends('admin.layout')

@section('title', isset($agenda) ? 'Ubah Agenda' : 'Tulis Agenda')
@section('heading', isset($agenda) ? 'Ubah Agenda' : 'Tulis Agenda')

@section('content')
<form action="{{ isset($agenda) ? route('admin.agendas.update', $agenda) : route('admin.agendas.store') }}" method="POST" enctype="multipart/form-data"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    @csrf
    @if (isset($agenda))
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-1.5 block text-sm font-bold text-navy-900">Judul Agenda</label>
        <input type="text" name="title" id="title" value="{{ old('title', $agenda->title ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('title')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-bold text-navy-900">Deskripsi</label>
        <textarea name="description" id="description" rows="5" required
                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('description', $agenda->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="location" class="mb-1.5 block text-sm font-bold text-navy-900">Lokasi</label>
        <input type="text" name="location" id="location" value="{{ old('location', $agenda->location ?? '') }}" placeholder="Lapangan Upacara"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('location')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="start_at" class="mb-1.5 block text-sm font-bold text-navy-900">Waktu Mulai</label>
            <input type="datetime-local" name="start_at" id="start_at"
                   value="{{ old('start_at', isset($agenda) ? $agenda->start_at->format('Y-m-d\TH:i') : '') }}" required
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('start_at')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="end_at" class="mb-1.5 block text-sm font-bold text-navy-900">Waktu Selesai</label>
            <input type="datetime-local" name="end_at" id="end_at"
                   value="{{ old('end_at', isset($agenda?->end_at) ? $agenda->end_at->format('Y-m-d\TH:i') : '') }}"
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            @error('end_at')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="cover_image" class="mb-1.5 block text-sm font-bold text-navy-900">Sampul (opsional)</label>
        @if (isset($agenda?->cover_image))
            <img src="{{ asset('storage/'.$agenda->cover_image) }}" alt="Sampul agenda" class="mb-3 h-32 rounded-2xl object-cover ring-1 ring-slate-200">
        @endif
        <input type="file" name="cover_image" id="cover_image" accept="image/*"
               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500">
        @error('cover_image')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <label class="flex cursor-pointer items-center gap-2 rounded-2xl bg-slate-50 p-4 text-sm font-bold text-navy-900 ring-1 ring-slate-100">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $agenda->is_published ?? false)) class="h-5 w-5 rounded accent-emerald-600">
        Tayangkan di situs
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.agendas.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

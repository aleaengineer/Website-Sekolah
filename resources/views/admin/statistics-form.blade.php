@extends('admin.layout')

@section('title', isset($statistic) ? 'Ubah Data Siswa' : 'Tambah Data Siswa')
@section('heading', isset($statistic) ? 'Ubah Data Siswa' : 'Tambah Data Siswa')

@section('content')
<form action="{{ isset($statistic) ? route('admin.statistics.update', $statistic) : route('admin.statistics.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 sm:grid-cols-3">
    @csrf
    @if (isset($statistic))
        @method('PUT')
    @endif

    <div>
        <label for="year" class="mb-1.5 block text-sm font-bold text-navy-900">Tahun</label>
        <input type="number" name="year" id="year" value="{{ old('year', $statistic->year ?? now()->year) }}" min="2000" max="2100" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('year')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="male_students" class="mb-1.5 block text-sm font-bold text-navy-900">Siswa Laki-laki</label>
        <input type="number" name="male_students" id="male_students" value="{{ old('male_students', $statistic->male_students ?? 0) }}" min="0" max="100000" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('male_students')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="female_students" class="mb-1.5 block text-sm font-bold text-navy-900">Siswa Perempuan</label>
        <input type="number" name="female_students" id="female_students" value="{{ old('female_students', $statistic->female_students ?? 0) }}" min="0" max="100000" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('female_students')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 sm:col-span-3">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.statistics.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

@extends('admin.layout')

@section('title', 'Profil Saya')
@section('heading', 'Profil Saya')

@section('content')
<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 sm:grid-cols-2">
    @csrf
    @method('PUT')

    <div class="sm:col-span-2">
        <p class="mb-1.5 block text-sm font-bold text-navy-900">Foto Profil</p>
        <div class="flex items-center gap-4">
            @if (auth()->user()->photo)
                <img src="{{ asset('storage/'.auth()->user()->photo) }}" alt="Foto profil" class="h-20 w-20 rounded-full object-cover ring-2 ring-emerald-200">
            @else
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-navy-900 text-2xl font-extrabold text-white">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
            @endif
            <input type="file" name="photo" id="photo" accept="image/*"
                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold">
        </div>
        <p class="mt-1 text-xs text-slate-400">Maks. 2 MB. Biarkan kosong bila tidak diganti.</p>
        @error('photo')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Lengkap</label>
        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="mb-1.5 block text-sm font-bold text-navy-900">Email (tidak dapat diubah)</label>
        <input type="email" id="email" value="{{ auth()->user()->email }}" disabled
               class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm text-slate-500 outline-none">
    </div>

    <div class="sm:col-span-2">
        <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-100">
            <p class="text-sm font-extrabold text-navy-900">Ganti Kata Sandi <span class="font-normal text-slate-400">(opsional)</span></p>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="current_password" class="mb-1.5 block text-sm font-bold text-navy-900">Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    @error('current_password')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-bold text-navy-900">Sandi Baru</label>
                    <input type="password" name="password" id="password" autocomplete="new-password"
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    @error('password')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-bold text-navy-900">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                </div>
            </div>
        </div>
    </div>

    <div class="sm:col-span-2">
        <button type="submit" class="rounded-2xl bg-navy-900 px-8 py-3.5 text-sm font-extrabold text-white transition hover:bg-navy-800">
            Simpan Profil
        </button>
    </div>
</form>
@endsection

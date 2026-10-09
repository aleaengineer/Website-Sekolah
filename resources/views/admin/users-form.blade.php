@extends('admin.layout')

@section('title', isset($editedUser) ? 'Ubah Pengguna' : 'Tambah Pengguna')
@section('heading', isset($editedUser) ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('content')
<form action="{{ isset($editedUser) ? route('admin.users.update', $editedUser) : route('admin.users.store') }}" method="POST"
      class="grid max-w-3xl gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8 sm:grid-cols-2">
    @csrf
    @if (isset($editedUser))
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama Lengkap</label>
        <input type="text" name="name" id="name" value="{{ old('name', $editedUser->name ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="mb-1.5 block text-sm font-bold text-navy-900">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $editedUser->email ?? '') }}" required
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('email')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <span class="mb-1.5 block text-sm font-bold text-navy-900">Role</span>
        @php
            $roles = [
                'admin' => ['Admin', 'Akses penuh termasuk kelola pengguna, log, dan backup'],
                'operator' => ['Operator', 'Semua konten, tanpa kelola pengguna'],
                'panitia' => ['Panitia', 'Khusus PPDB, gelombang, jalur, dan pesan'],
                'guru' => ['Guru', 'Hanya Berita dan Galeri'],
            ];
        @endphp
        <div class="grid gap-2 sm:grid-cols-2">
            @foreach ($roles as $value => [$label, $hint])
                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 px-4 py-3 transition has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:ring-2 has-checked:ring-emerald-200">
                    <input type="radio" name="role" value="{{ $value }}" @checked(old('role', $editedUser->role ?? 'operator') === $value) class="mt-1 h-4 w-4 shrink-0 accent-emerald-600">
                    <span>
                        <span class="block text-sm font-extrabold text-navy-900">{{ $label }}</span>
                        <span class="block text-xs text-slate-500">{{ $hint }}</span>
                    </span>
                </label>
            @endforeach
        </div>
        @error('role')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password" class="mb-1.5 block text-sm font-bold text-navy-900">Kata Sandi {{ isset($editedUser) ? '(kosongkan bila tidak diubah)' : '' }}</label>
        <input type="password" name="password" id="password" {{ isset($editedUser) ? '' : 'required' }} minlength="8"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('password')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 sm:col-span-2">
        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-sm font-extrabold text-white transition hover:brightness-110">
            Simpan
        </button>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
    </div>
</form>
@endsection

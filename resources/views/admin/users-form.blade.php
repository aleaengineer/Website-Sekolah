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
        <label for="role" class="mb-1.5 block text-sm font-bold text-navy-900">Role</label>
        <div class="relative">
            <select name="role" id="role" required
                    class="w-full appearance-none rounded-2xl border border-slate-200 bg-white py-3 pl-4 pr-11 text-sm font-semibold text-navy-900 shadow-sm outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                @foreach (['admin' => 'Admin — akses penuh termasuk kelola pengguna', 'operator' => 'Operator — semua konten, tanpa kelola pengguna', 'panitia' => 'Panitia — khusus PPDB, gelombang, jalur & pesan', 'guru' => 'Guru — hanya Berita & Galeri'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $editedUser->role ?? 'operator') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
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

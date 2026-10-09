@extends('admin.layout')

@section('title', 'Kategori Berita')
@section('heading', 'Kategori Berita')

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <form action="{{ isset($editing) ? route('admin.categories.update', $editing) : route('admin.categories.store') }}" method="POST" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @if (isset($editing))
            @method('PUT')
        @endif
        <h2 class="font-extrabold text-navy-900">{{ isset($editing) ? 'Ubah Kategori' : 'Tambah Kategori' }}</h2>
        @if (isset($editing))
            <p class="mt-1 text-xs text-slate-500">Dipakai {{ $editing->news_count }} berita. Slug ikut diperbarui mengikuti nama.</p>
        @endif
        <label for="name" class="mb-1.5 mt-4 block text-sm font-bold text-navy-900">Nama Kategori</label>
        <input type="text" name="name" id="name" value="{{ old('name', $editing->name ?? '') }}" required placeholder="mis. Prestasi"
               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
        <button type="submit" class="mt-4 w-full rounded-2xl bg-navy-900 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-navy-800">
            {{ isset($editing) ? 'Simpan Perubahan' : 'Tambah' }}
        </button>
        @if (isset($editing))
            <a href="{{ route('admin.categories.index') }}" class="mt-2 block text-center text-sm font-semibold text-slate-500 hover:text-navy-900">Batal</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 lg:col-span-2">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Berita</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-navy-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $category->news_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini? Berita di dalamnya menjadi tanpa kategori.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection

@extends('admin.layout')

@section('title', 'Jalur PPDB')
@section('heading', 'Jalur PPDB')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">{{ $jalurs->total() }} jalur &bull; yang aktif tampil di formulir pendaftaran</p>
    <a href="{{ route('admin.jalurs.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tambah Jalur</a>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Jalur</th>
                    <th class="px-6 py-4">Pendaftar</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($jalurs as $jalur)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-bold text-navy-900">{{ $jalur->name }}</span>
                            <span class="block font-mono text-xs text-slate-400">{{ $jalur->slug }}</span>
                            @if ($jalur->description)
                                <span class="block max-w-xs truncate text-xs text-slate-500">{{ $jalur->description }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $jalur->registrations_count }}</td>
                        <td class="px-6 py-4">
                            @if ($jalur->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.jalurs.edit', $jalur) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.jalurs.destroy', $jalur) }}" method="POST" onsubmit="return confirm('Hapus jalur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Belum ada jalur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $jalurs->links() }}</div>
@endsection

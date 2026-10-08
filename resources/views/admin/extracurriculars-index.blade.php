@extends('admin.layout')

@section('title', 'Ekstrakurikuler')
@section('heading', 'Ekstrakurikuler')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">{{ $extracurriculars->total() }} kegiatan</p>
    <a href="{{ route('admin.extracurriculars.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tambah Kegiatan</a>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Kegiatan</th>
                    <th class="px-6 py-4">Jadwal</th>
                    <th class="px-6 py-4">Pembina</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($extracurriculars as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-navy-900">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->schedule ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->coach_name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if ($item->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.extracurriculars.edit', $item) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.extracurriculars.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada kegiatan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $extracurriculars->links() }}</div>
@endsection

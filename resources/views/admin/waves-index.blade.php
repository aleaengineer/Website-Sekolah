@extends('admin.layout')

@section('title', 'Gelombang PPDB')
@section('heading', 'Gelombang PPDB')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">{{ $waves->total() }} gelombang &bull; kuota terisi dihitung dari pendaftar per gelombang</p>
    <a href="{{ route('admin.waves.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tambah Gelombang</a>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Gelombang</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4">Kuota</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($waves as $wave)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-bold text-navy-900">{{ $wave->name }}</span>
                            @if ($wave->description)
                                <span class="block max-w-xs truncate text-xs text-slate-500">{{ $wave->description }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $wave->start_date->translatedFormat('d M Y') }} &ndash; {{ $wave->end_date->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $wave->registrations_count }} / {{ $wave->quota }} <span class="text-xs text-slate-400">({{ max(0, $wave->quota - $wave->registrations_count) }} sisa)</span></td>
                        <td class="px-6 py-4">
                            @if ($wave->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.waves.edit', $wave) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.waves.destroy', $wave) }}" method="POST" onsubmit="return confirm('Hapus gelombang ini? Pendaftar lama tetap tersimpan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada gelombang. Tambahkan Gelombang 1 dan 2 tahun ajaran berjalan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $waves->links() }}</div>
@endsection

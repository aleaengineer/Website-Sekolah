@extends('admin.layout')

@section('title', 'Data Siswa')
@section('heading', 'Data Siswa per Tahun')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">Isi jumlah peserta didik laki-laki dan perempuan setiap tahun untuk grafik pertumbuhan di dashboard.</p>
    <a href="{{ route('admin.statistics.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tambah Tahun</a>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Tahun</th>
                    <th class="px-6 py-4">Laki-laki</th>
                    <th class="px-6 py-4">Perempuan</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Tumbuh</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php
                    $previousTotal = null;
                @endphp
                @forelse ($statistics as $stat)
                    @php
                        $growth = $previousTotal !== null && $previousTotal > 0 ? ($stat->total - $previousTotal) / $previousTotal * 100 : null;
                        $previousTotal = $stat->total;
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-extrabold text-navy-900">{{ $stat->year }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($stat->male_students) }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($stat->female_students) }}</td>
                        <td class="px-6 py-4 font-bold text-navy-900">{{ number_format($stat->total) }}</td>
                        <td class="px-6 py-4">
                            @if ($growth === null)
                                <span class="text-slate-300">—</span>
                            @elseif ($growth >= 0)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">+{{ number_format($growth, 1) }}%</span>
                            @else
                                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">{{ number_format($growth, 1) }}%</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.statistics.edit', $stat) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.statistics.destroy', $stat) }}" method="POST" onsubmit="return confirm('Hapus data tahun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada data. Klik "Tambah Tahun" untuk mengisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $statistics->links() }}</div>
@endsection

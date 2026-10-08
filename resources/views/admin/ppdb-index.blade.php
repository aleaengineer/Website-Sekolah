@extends('admin.layout')

@section('title', 'PPDB Masuk')
@section('heading', 'Pendaftar PPDB')

@section('content')
<form action="{{ route('admin.ppdb.index') }}" method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / nomor pendaftaran…"
           class="grow rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
    <select name="status" onchange="this.form.submit()"
            class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">
        <option value="">Semua status</option>
        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-navy-800">Cari</button>
    <a href="{{ route('admin.ppdb.export', ['status' => request('status')]) }}" class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-navy-900 transition hover:bg-slate-50">Ekspor Excel</a>
</form>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">No. Pendaftaran</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Jalur</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($registrations as $reg)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-navy-900">{{ $reg->registration_number }}</td>
                        <td class="px-6 py-4 font-bold text-navy-900">{{ $reg->student_name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ ucfirst($reg->jalur) }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold
                                {{ $reg->status === 'diterima' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $reg->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                                {{ $reg->status === 'diverifikasi' ? 'bg-sky-100 text-sky-800' : '' }}
                                {{ $reg->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : '' }}">
                                {{ $statuses[$reg->status] ?? $reg->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.ppdb.show', $reg) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Detail</a>
                                <form action="{{ route('admin.ppdb.destroy', $reg) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada pendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $registrations->links() }}</div>
@endsection

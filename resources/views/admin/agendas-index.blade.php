@extends('admin.layout')

@section('title', 'Agenda')
@section('heading', 'Agenda Kegiatan')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">{{ $agendas->total() }} agenda &bull; yang tayang tampil di halaman publik</p>
    <a href="{{ route('admin.agendas.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tulis Agenda</a>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Judul</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($agendas as $agenda)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-bold text-navy-900">{{ \Illuminate\Support\Str::limit($agenda->title, 60) }}</span>
                            @if ($agenda->location)
                                <span class="block text-xs text-slate-500">{{ $agenda->location }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $agenda->start_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @if ($agenda->is_published)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Tayang</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">Draf</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.agendas.edit', $agenda) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.agendas.destroy', $agenda) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Belum ada agenda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $agendas->links() }}</div>
@endsection

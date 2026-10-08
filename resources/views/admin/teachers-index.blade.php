@extends('admin.layout')

@section('title', 'Guru & Tendik')
@section('heading', 'Guru & Tenaga Kependidikan')

@section('content')
@if (session('import_errors'))
    <div class="mb-5 rounded-2xl border border-amber-300 bg-amber-50 px-5 py-4 text-sm text-amber-900" role="alert">
        <p class="font-bold">Sebagian baris gagal diimpor:</p>
        <ul class="mt-1 list-disc pl-5">
            @foreach (session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-5 rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
        <div class="grow">
            <h2 class="font-extrabold text-navy-900">Impor dari Excel</h2>
        </div>
        <a href="{{ route('admin.teachers.template') }}" class="shrink-0 self-start whitespace-nowrap rounded-2xl border border-slate-200 px-4 py-2 text-center text-xs font-bold text-navy-900 transition hover:bg-slate-50 lg:self-center">Unduh Template</a>
        <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data" class="flex shrink-0 gap-2">
            @csrf
            <input type="file" name="import" accept=".xlsx,.xls,.csv" required
                   class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition file:mr-3 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-1.5 file:text-sm file:font-bold">
            <button type="submit" class="shrink-0 self-center rounded-2xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-700">Impor</button>
        </form>
        <a href="{{ route('admin.teachers.export') }}" class="shrink-0 self-start whitespace-nowrap rounded-2xl bg-navy-900 px-4 py-2 text-center text-xs font-bold text-white transition hover:bg-navy-800 lg:self-center">Ekspor Excel</a>
    </div>
    @error('import')<p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-slate-500">{{ $teachers->total() }} orang</p>
    <a href="{{ route('admin.teachers.create') }}" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Tambah Guru</a>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Jabatan</th>
                    <th class="px-6 py-4">Mapel</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($teachers as $teacher)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if ($teacher->photo)
                                    <img src="{{ asset('storage/'.$teacher->photo) }}" alt="{{ $teacher->name }}" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200">
                                @else
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-navy-900 text-sm font-extrabold text-white">{{ strtoupper(mb_substr($teacher->name, 0, 1)) }}</span>
                                @endif
                                <span class="font-bold text-navy-900">{{ $teacher->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $teacher->position }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $teacher->subject ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if ($teacher->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Ubah</a>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $teachers->links() }}</div>
@endsection

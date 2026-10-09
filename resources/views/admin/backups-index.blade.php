@extends('admin.layout')

@section('title', 'Backup Database')
@section('heading', 'Backup Database')

@section('content')
<div class="mb-5 flex flex-col gap-3 rounded-3xl bg-amber-50 p-5 text-sm text-amber-900 ring-1 ring-amber-200">
    <p><span class="font-extrabold">Perhatian:</span> restore menimpa seluruh isi database dengan berkas yang diunggah. Buat backup baru dulu sebelum restore, dan lakukan di luar jam sibuk.</p>
    <div class="flex flex-wrap gap-2">
        <form action="{{ route('admin.backups.store') }}" method="POST">
            @csrf
            <button type="submit" class="rounded-2xl bg-navy-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-navy-800">+ Buat Backup Sekarang</button>
        </form>
    </div>
</div>

<div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
    <h2 class="font-extrabold text-navy-900">Restore dari Berkas</h2>
    <form action="{{ route('admin.backups.restore') }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center" onsubmit="return confirm('Restore akan MENIMPA database saat ini. Lanjutkan?')">
        @csrf
        <input type="file" name="backup" accept=".sql,.sqlite,.db" required
               class="grow rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">
        <button type="submit" class="rounded-2xl bg-rose-600 px-5 py-2.5 text-sm font-bold text-white transition hover:brightness-110">Restore</button>
    </form>
    @error('backup')<p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="mt-6 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Berkas</th>
                    <th class="px-6 py-4">Ukuran</th>
                    <th class="px-6 py-4">Dibuat</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($files as $file)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-navy-900">{{ $file['name'] }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($file['size'] / 1024, 1) }} KB</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::createFromTimestamp($file['modified'])->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.backups.download', $file['name']) }}" class="rounded-lg bg-navy-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-navy-800">Unduh</a>
                                <form action="{{ route('admin.backups.destroy', $file['name']) }}" method="POST" onsubmit="return confirm('Hapus backup ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Belum ada backup. Buat backup pertama sekarang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

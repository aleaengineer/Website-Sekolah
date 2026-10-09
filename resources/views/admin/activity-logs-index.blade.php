@extends('admin.layout')

@section('title', 'Log Aktivitas')
@section('heading', 'Log Aktivitas')

@section('content')
<form action="{{ route('admin.activity-logs.index') }}" method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
    <select name="action" onchange="this.form.submit()"
            class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">
        <option value="">Semua aksi</option>
        @foreach ($actions as $value => $label)
            <option value="{{ $value }}" @selected(request('action') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="user_id" onchange="this.form.submit()"
            class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-emerald-500">
        <option value="">Semua pengguna</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>
        @endforeach
    </select>
    <a href="{{ route('admin.activity-logs.index') }}" class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-navy-900 transition hover:bg-slate-50">Atur Ulang</a>
</form>

<div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[820px] text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Pengguna</th>
                    <th class="px-6 py-4">Aksi</th>
                    <th class="px-6 py-4">Keterangan</th>
                    <th class="px-6 py-4">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-6 py-4 font-bold text-navy-900">{{ $log->user?->name ?? 'Sistem' }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">{{ $actions[$log->action] ?? $log->action }}</span>
                            <span class="block text-xs text-slate-400">{{ $log->subjectLabel() }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $log->description ?? '—' }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada aktivitas tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $logs->links() }}</div>
@endsection

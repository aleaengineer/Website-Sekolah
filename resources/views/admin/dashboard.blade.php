@extends('admin.layout')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-900 via-navy-800 to-emerald-800 p-7 text-white shadow sm:p-8">
    <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-amber-400/20 blur-3xl" aria-hidden="true"></div>
    <div class="relative">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h2 class="mt-1 font-serif text-2xl font-bold sm:text-3xl">Halo, {{ auth()->user()->name }}!</h2>
        <p class="mt-1 text-sm text-slate-200">Kelola seluruh konten website SMP Negeri Satu Atap I Sidamulih dari sini.</p>
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('admin.news.create') }}" class="rounded-xl bg-amber-400 px-4 py-2 text-xs font-extrabold text-navy-950 transition hover:brightness-105">+ Tulis Berita</a>
            <a href="{{ route('admin.ppdb.index') }}" class="rounded-xl bg-white/15 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/25">Proses PPDB ({{ $stats['ppdbPending'] }})</a>
        </div>
    </div>
</div>

<div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @php
        $cards = [
            ['Berita Tayang', $stats['news'], 'Berita', 'from-emerald-500 to-teal-600', route('admin.news.index')],
            ['Guru Aktif', $stats['teachers'], 'Guru', 'from-navy-600 to-navy-900', route('admin.teachers.index')],
            ['Foto Galeri', $stats['galleries'], 'Galeri', 'from-amber-400 to-orange-500', route('admin.galleries.index')],
            ['Ekstrakurikuler', $stats['extracurriculars'], 'Kegiatan', 'from-sky-500 to-indigo-600', route('admin.extracurriculars.index')],
        ];
    @endphp
    @foreach ($cards as $card)
        <a href="{{ $card[4] }}" class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $card[3] }}" aria-hidden="true"></div>
            <p class="text-4xl font-extrabold text-navy-900">{{ $card[1] }}</p>
            <p class="mt-1 text-sm font-bold text-navy-900">{{ $card[0] }}</p>
            <p class="text-xs text-slate-400">Kelola {{ $card[2] }} &rarr;</p>
        </a>
    @endforeach
</div>

<div class="mt-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 class="font-extrabold text-navy-900">Pertumbuhan Siswa per Tahun</h2>
            <div class="mt-1.5 flex gap-4 text-xs font-semibold text-slate-500">
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-sky-500"></span>Laki-laki</span>
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-emerald-500"></span>Perempuan</span>
            </div>
        </div>
        <a href="{{ route('admin.statistics.index') }}" class="rounded-xl bg-navy-900 px-4 py-2 text-xs font-bold text-white transition hover:bg-navy-800">Kelola Data</a>
    </div>

    @if ($yearlyStats->isNotEmpty())
        @php
            $previousTotal = null;
            $maxTotal = max(1, $yearlyStats->max('total'));
        @endphp
        <div class="mt-6 flex items-stretch gap-3 overflow-x-auto pb-1">
            @foreach ($yearlyStats as $stat)
                @php
                    $maleShare = $stat->total > 0 ? $stat->male_students / $stat->total * 100 : 0;
                    $barHeight = max(6, $stat->total / $maxTotal * 100);
                    $growth = $previousTotal !== null && $previousTotal > 0 ? ($stat->total - $previousTotal) / $previousTotal * 100 : null;
                    $previousTotal = $stat->total;
                @endphp
                <div class="flex min-w-14 flex-1 flex-col items-center gap-1.5" title="{{ $stat->year }}: {{ $stat->male_students }} L + {{ $stat->female_students }} P = {{ $stat->total }} siswa">
                    <span class="text-sm font-extrabold text-navy-900">{{ $stat->total }}</span>
                    <div class="flex h-44 w-full max-w-16 items-end">
                        <div class="flex w-full flex-col justify-end overflow-hidden rounded-t-xl bg-slate-100" style="height: {{ $barHeight }}%">
                            <div class="w-full bg-emerald-500" style="height: {{ 100 - $maleShare }}%"></div>
                            <div class="w-full bg-sky-500" style="height: {{ $maleShare }}%"></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $stat->year }}</span>
                    @if ($growth === null)
                        <span class="text-[11px] font-bold text-slate-300">—</span>
                    @elseif ($growth >= 0)
                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-emerald-700">+{{ number_format($growth, 1) }}%</span>
                    @else
                        <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-bold text-rose-600">{{ number_format($growth, 1) }}%</span>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
            Belum ada data siswa per tahun. <a href="{{ route('admin.statistics.create') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Tambah data pertama</a>.
        </div>
    @endif
</div>

<div class="mt-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="font-extrabold text-navy-900">Rekap PPDB</h2>
        <a href="{{ route('admin.ppdb.index') }}" class="rounded-xl bg-navy-900 px-4 py-2 text-xs font-bold text-white transition hover:bg-navy-800">Kelola Pendaftar</a>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        @foreach ($statusLabels as $value => $label)
            <a href="{{ route('admin.ppdb.index', ['status' => $value]) }}" class="rounded-full bg-slate-100 px-4 py-1.5 text-xs font-extrabold text-slate-700 transition hover:bg-slate-200">
                {{ $label }}: {{ $ppdbByStatus[$value] ?? 0 }}
            </a>
        @endforeach
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        <div>
            <h3 class="text-sm font-extrabold text-navy-900">Per Gelombang</h3>
            <ul class="mt-3 flex flex-col gap-2.5">
                @forelse ($ppdbWaves as $wave)
                    @php($filled = $wave->quota > 0 ? min(100, $wave->registrations_count / $wave->quota * 100) : 0)
                    <li>
                        <a href="{{ route('admin.ppdb.index', ['ppdb_wave_id' => $wave->id]) }}" class="block rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-100 transition hover:bg-slate-100">
                            <span class="flex items-center justify-between gap-2 text-sm">
                                <span class="font-bold text-navy-900">{{ $wave->name }}</span>
                                <span class="font-mono text-xs text-slate-500">{{ $wave->registrations_count }}/{{ $wave->quota }}</span>
                            </span>
                            <span class="mt-2 block h-2 overflow-hidden rounded-full bg-slate-200">
                                <span class="block h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-600" style="width: {{ $filled }}%"></span>
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-400">Belum ada gelombang. <a href="{{ route('admin.waves.create') }}" class="font-bold text-emerald-700">Buat gelombang</a>.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="text-sm font-extrabold text-navy-900">Per Jalur</h3>
            <ul class="mt-3 flex flex-col gap-2.5">
                @forelse ($ppdbByJalur as $slug => $total)
                    <li class="flex items-center justify-between gap-2 rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-100">
                        <span class="text-sm font-bold text-navy-900">{{ $jalurNames[$slug] ?? ucfirst($slug) }}</span>
                        <span class="rounded-full bg-navy-900 px-3 py-1 text-xs font-extrabold text-white">{{ $total }}</span>
                    </li>
                @empty
                    <li class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-400">Belum ada pendaftar.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="mt-5 grid gap-5 lg:grid-cols-2">
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-navy-900">PPDB Menunggu</h2>
            <div class="flex items-center gap-3">
                @if ($stats['ppdbPending'] > 0)
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-800">{{ $stats['ppdbPending'] }} antre</span>
                @endif
                <a href="{{ route('admin.ppdb.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-800">Lihat semua &rarr;</a>
            </div>
        </div>
        <ul class="mt-4 flex flex-col gap-2.5">
            @forelse ($latestRegistrations as $reg)
                <li>
                    <a href="{{ route('admin.ppdb.show', $reg) }}" class="flex items-center justify-between gap-3 rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-100 transition hover:bg-slate-100">
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold text-navy-900">{{ $reg->student_name }}</span>
                            <span class="block font-mono text-[11px] text-slate-500">{{ $reg->registration_number }}</span>
                        </span>
                        <span class="shrink-0 rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold text-amber-800">{{ $reg->status }}</span>
                    </a>
                </li>
            @empty
                <li class="rounded-2xl border border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-400">Belum ada pendaftaran masuk.</li>
            @endforelse
        </ul>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-navy-900">Pesan Masuk</h2>
            <div class="flex items-center gap-3">
                @if ($stats['messagesUnread'] > 0)
                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-extrabold text-rose-700">{{ $stats['messagesUnread'] }} baru</span>
                @endif
                <a href="{{ route('admin.messages.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-800">Lihat semua &rarr;</a>
            </div>
        </div>
        <ul class="mt-4 flex flex-col gap-2.5">
            @forelse ($latestMessages as $msg)
                <li class="rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-100">
                    <p class="flex items-center gap-2 text-sm font-bold text-navy-900">
                        @unless ($msg->is_read)
                            <span class="h-2 w-2 shrink-0 rounded-full bg-rose-500" aria-hidden="true"></span>
                        @endunless
                        <span class="truncate">{{ $msg->name }} <span class="font-normal text-slate-500">— {{ $msg->contact }}</span></span>
                    </p>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $msg->message }}</p>
                </li>
            @empty
                <li class="rounded-2xl border border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-400">Belum ada pesan masuk.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — SMP Negeri Satu Atap I Sidamulih</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('admin-styles')
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="flex min-h-screen">
        @php
            $allStaff = ['admin', 'operator', 'guru'];
            $everyone = ['admin', 'operator', 'guru', 'panitia'];
            $ppdbTeam = ['admin', 'operator', 'panitia'];
            $managers = ['admin', 'operator'];
            $menu = [
                ['admin.dashboard', 'Dashboard', 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z', 'admin.dashboard', 0, $everyone],
                ['admin.news.index', 'Berita', 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z', 'admin.news.*', 0, $allStaff],
                ['admin.teachers.index', 'Guru & Tendik', 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', 'admin.teachers.*', 0, $managers],
                ['admin.galleries.index', 'Galeri', 'm2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z', 'admin.galleries.*', 0, $allStaff],
                ['admin.extracurriculars.index', 'Ekstrakurikuler', 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.563.563 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z', 'admin.extracurriculars.*', 0, $managers],
                ['admin.statistics.index', 'Data Siswa', 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'admin.statistics.*', 0, $managers],
                ['admin.announcements.index', 'Pengumuman', 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0', 'admin.announcements.*', 0, $managers],
                ['admin.agendas.index', 'Agenda', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5', 'admin.agendas.*', 0, $managers],
                ['admin.calendars.index', 'Kalender', 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'admin.calendars.*', 0, $managers],
                ['admin.documents.index', 'Dokumen', 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z', 'admin.documents.*', 0, $managers],
                ['admin.heroes.index', 'Hero Beranda', 'M2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z', 'admin.heroes.*', 0, $managers],
                ['admin.ppdb.index', 'PPDB Masuk', 'M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75', 'admin.ppdb.*', $adminBadges['ppdb'] ?? 0, $ppdbTeam],
                ['admin.waves.index', 'Gelombang PPDB', 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'admin.waves.*', 0, $ppdbTeam],
                ['admin.jalurs.index', 'Jalur PPDB', 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z', 'admin.jalurs.*', 0, $ppdbTeam],
                ['admin.messages.index', 'Pesan Masuk', 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75', 'admin.messages.*', $adminBadges['messages'] ?? 0, $ppdbTeam],
                ['admin.settings', 'Pengaturan', 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28Z', 'admin.settings*', 0, $managers],
                ['admin.users.index', 'Pengguna', 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z', 'admin.users.*', 0, ['admin']],
                ['admin.activity-logs.index', 'Log Aktivitas', 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'admin.activity-logs.*', 0, ['admin']],
                ['admin.backups.index', 'Backup DB', 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z', 'admin.backups.*', 0, ['admin']],
                ['admin.profile.edit', 'Profil Saya', 'M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0v.257a.75.75 0 0 1-.75.75h-13.5a.75.75 0 0 1-.75-.75v-.257Z', 'admin.profile.*', 0, $everyone],
            ];
        @endphp

        <aside class="sticky top-0 hidden h-screen w-68 shrink-0 flex-col bg-navy-950 text-slate-200 lg:flex">
            <div class="bg-gradient-to-br from-navy-900 via-navy-950 to-emerald-950 px-6 pb-6 pt-6">
                <div class="flex items-center gap-3">
                    <x-logo class="h-11 w-11 drop-shadow" />
                    <div>
                        <p class="text-sm font-extrabold text-white">Admin Panel</p>
                        <p class="text-[11px] text-slate-400">SMPN Satu Atap I Sidamulih</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <span class="rounded-lg bg-amber-400/15 px-2.5 py-1 text-[11px] font-bold text-amber-300">NPSN 20253310</span>
                    <span class="rounded-lg bg-emerald-500/15 px-2.5 py-1 text-[11px] font-bold text-emerald-300">Akreditasi B</span>
                </div>
            </div>

            <nav class="admin-scroll flex flex-col gap-1 overflow-y-auto px-3 py-4" aria-label="Menu admin">
                @foreach ($menu as [$route, $label, $icon, $match, $badge, $roles])
                    @continue(! auth()->user()->hasRole(...$roles))
                    @php($active = $match === 'admin.dashboard' ? request()->routeIs('admin.dashboard') : request()->routeIs($match))
                    <a href="{{ route($route) }}"
                       class="group flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $active ? 'bg-white/10 text-white shadow' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $active ? 'bg-gradient-to-br from-amber-400 to-orange-500 text-navy-950' : 'bg-white/5 text-slate-400 group-hover:text-amber-300' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        </span>
                        <span class="grow">{{ $label }}</span>
                        @if ($badge > 0)
                            <span class="rounded-full bg-rose-500 px-2 py-0.5 text-[11px] font-extrabold text-white">{{ $badge }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto border-t border-white/10 p-4">
                <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-3">
                    @if (auth()->user()->photo)
                        <img src="{{ asset('storage/'.auth()->user()->photo) }}" alt="Foto profil" class="h-10 w-10 shrink-0 rounded-full object-cover ring-1 ring-white/20">
                    @else
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-sm font-extrabold text-white">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                    <div class="min-w-0 grow">
                        <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="mt-2 flex gap-2">
                    <a href="{{ route('home') }}" target="_blank" class="grow rounded-xl bg-white/5 px-3 py-2 text-center text-xs font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">Lihat Situs</a>
                    <form action="{{ route('logout') }}" method="POST" class="grow">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-rose-500/10 px-3 py-2 text-xs font-bold text-rose-300 transition hover:bg-rose-500/20">Keluar</button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 grow flex-col">
            <header class="sticky top-0 z-40 flex items-center gap-3 border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur sm:px-8">
                <button type="button" id="admin-nav-toggle" class="rounded-xl p-2 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="min-w-0 grow">
                    <h1 class="truncate text-lg font-extrabold text-navy-900">@yield('heading', 'Dashboard')</h1>
                    <p class="hidden text-xs text-slate-400 sm:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-navy-900 text-sm font-extrabold text-white lg:hidden">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
            </header>

            <nav id="admin-mobile-nav" class="hidden border-b border-slate-200 bg-white px-4 py-3 lg:hidden" aria-label="Menu admin seluler">
                <div class="flex flex-col gap-1">
                    @foreach ($menu as [$route, $label, $icon, $match, $badge, $roles])
                        @continue(! auth()->user()->hasRole(...$roles))
                        <a href="{{ route($route) }}" class="flex items-center justify-between rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            <span>{{ $label }}</span>
                            @if ($badge > 0)
                                <span class="rounded-full bg-rose-500 px-2 py-0.5 text-[11px] font-extrabold text-white">{{ $badge }}</span>
                            @endif
                        </a>
                    @endforeach
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-xl px-4 py-2.5 text-left text-sm font-semibold text-rose-600 hover:bg-slate-100">Keluar</button>
                    </form>
                </div>
            </nav>

            <main class="mx-auto w-full max-w-6xl grow px-4 py-6 sm:px-8">
                @if (session('success'))
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800" role="alert">
                        <p class="font-bold">Periksa kembali isian berikut:</p>
                        <ul class="mt-1 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="px-4 pb-5 pt-1 text-center text-xs text-slate-400 sm:px-8">
                Admin Panel &bull; SMP Negeri Satu Atap I Sidamulih &bull; {{ now()->year }}
            </footer>
        </div>
    </div>

    @pushOnce('admin-scripts')
    <script>
        document.getElementById('admin-nav-toggle')?.addEventListener('click', function () {
            document.getElementById('admin-mobile-nav')?.classList.toggle('hidden');
        });
    </script>
    @endPushOnce
    @stack('admin-scripts')
</body>
</html>

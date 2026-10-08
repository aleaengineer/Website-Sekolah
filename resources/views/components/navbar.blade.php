@php
    $links = [
        ['route' => 'home', 'label' => 'Beranda', 'match' => 'home'],
        ['route' => 'profile', 'label' => 'Profil', 'match' => 'profile'],
        ['route' => 'academic', 'label' => 'Akademik', 'match' => 'academic'],
        ['route' => 'news.index', 'label' => 'Berita', 'match' => 'news.*'],
        ['route' => 'announcements', 'label' => 'Pengumuman', 'match' => 'announcements'],
        ['route' => 'gallery', 'label' => 'Galeri', 'match' => 'gallery'],
        ['route' => 'contact.index', 'label' => 'Kontak', 'match' => 'contact.*'],
    ];
@endphp

<div class="bg-navy-950 text-xs text-slate-200">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2 px-4 py-2 sm:px-6 lg:px-8">
        <p class="font-medium">NPSN {{ $settings['school.npsn'] ?? '20253310' }} &bull; Akreditasi {{ $settings['school.accreditation'] ?? 'B' }}</p>
        <p class="hidden sm:block">{{ $settings['school.email'] ?? '' }}</p>
    </div>
</div>

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <x-logo />
            <span>
                <span class="block text-[11px] font-bold uppercase tracking-widest text-emerald-700">SMP Negeri</span>
                <span class="block text-base font-extrabold leading-tight text-navy-900 sm:text-lg">Satu Atap I Sidamulih</span>
            </span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ request()->routeIs($link['match']) ? 'bg-navy-900 text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-navy-900' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('ppdb.index') }}"
               class="ml-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 px-5 py-2.5 text-sm font-bold text-navy-950 shadow transition hover:brightness-105">
                PPDB
            </a>
        </nav>

        <button type="button" id="nav-toggle" class="rounded-xl p-2 text-navy-900 hover:bg-slate-100 lg:hidden" aria-label="Buka menu" aria-expanded="false">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <nav id="mobile-nav" class="hidden border-t border-slate-200 bg-white px-4 py-3 lg:hidden" aria-label="Navigasi seluler">
        <div class="flex flex-col gap-1">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs($link['match']) ? 'bg-navy-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('ppdb.index') }}" class="mt-1 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 px-4 py-2.5 text-center text-sm font-bold text-navy-950">
                PPDB / SPMB
            </a>
        </div>
    </nav>
</header>

@pushOnce('scripts')
<script>
    document.getElementById('nav-toggle')?.addEventListener('click', function () {
        const nav = document.getElementById('mobile-nav');
        const open = nav.classList.toggle('hidden');
        this.setAttribute('aria-expanded', String(!open));
    });
</script>
@endPushOnce

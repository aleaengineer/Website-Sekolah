<footer class="bg-navy-950 text-slate-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
        <div>
            <div class="flex items-center gap-3">
                <x-logo />
                <p class="text-base font-extrabold leading-tight text-white">{{ $settings['school.name'] ?? 'SMP Negeri Satu Atap I Sidamulih' }}</p>
            </div>
            <p class="mt-4 text-sm leading-relaxed">{{ $settings['school.description'] ?? '' }}</p>
            <div class="mt-4 flex gap-2">
                <span class="rounded-lg bg-white/10 px-3 py-1 text-xs font-bold text-amber-300">NPSN {{ $settings['school.npsn'] ?? '20253310' }}</span>
                <span class="rounded-lg bg-white/10 px-3 py-1 text-xs font-bold text-emerald-300">Akreditasi {{ $settings['school.accreditation'] ?? 'B' }}</span>
            </div>
        </div>

        <nav aria-label="Tautan cepat">
            <p class="text-sm font-bold uppercase tracking-widest text-white">Jelajahi</p>
            <ul class="mt-4 flex flex-col gap-2.5 text-sm">
                <li><a class="hover:text-amber-300" href="{{ route('profile') }}">Profil Sekolah</a></li>
                <li><a class="hover:text-amber-300" href="{{ route('academic') }}">Akademik & Ekstrakurikuler</a></li>
                <li><a class="hover:text-amber-300" href="{{ route('news.index') }}">Berita</a></li>
                <li><a class="hover:text-amber-300" href="{{ route('gallery') }}">Galeri</a></li>
                <li><a class="hover:text-amber-300" href="{{ route('ppdb.index') }}">PPDB / SPMB</a></li>
            </ul>
        </nav>

        <div>
            <p class="text-sm font-bold uppercase tracking-widest text-white">Kontak</p>
            <ul class="mt-4 flex flex-col gap-2.5 text-sm">
                <li>{{ $settings['school.address'] ?? '' }}, {{ $settings['school.village'] ?? '' }}, Kec. {{ $settings['school.district'] ?? '' }}, Kab. {{ $settings['school.regency'] ?? '' }}, {{ $settings['school.province'] ?? '' }}</li>
                <li>{{ $settings['school.phone'] ?? '' }}</li>
                <li>{{ $settings['school.email'] ?? '' }}</li>
            </ul>
        </div>

        <div>
            <p class="text-sm font-bold uppercase tracking-widest text-white">Jam Layanan</p>
            <ul class="mt-4 flex flex-col gap-2.5 text-sm">
                <li>Senin – Jumat: 07.00 – 15.00 WIB</li>
                <li>Sabtu: 07.00 – 11.00 WIB</li>
                <li>Ahad & libur nasional: tutup</li>
            </ul>
            <a href="{{ route('ppdb.index') }}" class="mt-5 inline-block rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-2.5 text-sm font-bold text-white shadow transition hover:brightness-110">
                Daftar PPDB Sekarang
            </a>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-5 text-xs sm:flex-row sm:px-6 lg:px-8">
            <p>&copy; {{ now()->year }} {{ $settings['school.name'] ?? '' }}. Hak cipta dilindungi.</p>
            <p>Kec. Sidamulih, Kab. Pangandaran, Jawa Barat</p>
        </div>
    </div>
</footer>

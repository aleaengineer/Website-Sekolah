<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Admin — SMP Negeri Satu Atap I Sidamulih</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-navy-950 p-4 sm:p-8">
    <div class="absolute inset-0 bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-900" aria-hidden="true"></div>
    <div class="absolute -left-24 -top-24 h-96 w-96 rounded-full bg-amber-400/15 blur-3xl" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -right-16 h-[28rem] w-[28rem] rounded-full bg-emerald-500/15 blur-3xl" aria-hidden="true"></div>

    <div class="relative grid w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl md:grid-cols-5">
        <div class="relative flex flex-col justify-between gap-8 overflow-hidden bg-gradient-to-br from-navy-900 via-navy-800 to-emerald-800 p-8 text-white sm:p-10 md:col-span-2">
            <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-amber-400/20 blur-2xl" aria-hidden="true"></div>
            <div class="absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-emerald-400/20 blur-2xl" aria-hidden="true"></div>

            <div class="relative">
                <x-logo class="h-16 w-16 drop-shadow-lg" />
                <p class="mt-5 text-[11px] font-bold uppercase tracking-[0.25em] text-amber-300">SMP Negeri</p>
                <h1 class="mt-1 font-serif text-2xl font-bold leading-snug sm:text-3xl">Satu Atap I Sidamulih</h1>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold">NPSN 20253310</span>
                    <span class="rounded-full bg-emerald-500 px-3 py-1 text-[11px] font-extrabold">Akreditasi B</span>
                </div>
            </div>

            <div class="relative">
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">
                    <p class="text-sm italic leading-relaxed text-slate-100">"Terwujudnya peserta didik yang beriman, cerdas, terampil, mandiri, dan berakhlak mulia."</p>
                    <p class="mt-3 text-xs font-bold uppercase tracking-widest text-amber-300">— Visi Sekolah</p>
                </div>
                <p class="mt-5 text-xs text-slate-300">Jl. Karanganyar No.127 Kalijati, Sidamulih, Pangandaran</p>
            </div>
        </div>

        <div class="flex flex-col justify-center p-8 sm:p-10 md:col-span-3">
            <div class="flex items-center gap-2">
                <span class="h-1 w-10 rounded-full bg-amber-400"></span>
                <span class="h-1 w-4 rounded-full bg-emerald-500"></span>
                <span class="h-1 w-10 rounded-full bg-navy-800"></span>
            </div>
            <h2 class="mt-4 font-serif text-2xl font-bold text-navy-950 sm:text-3xl">Selamat Datang Kembali</h2>
            <p class="mt-1.5 text-sm text-slate-500">Masuk ke panel admin untuk mengelola konten website sekolah.</p>

            <form action="{{ route('login.store') }}" method="POST" class="mt-7 flex flex-col gap-4">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-bold text-navy-900">Email</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="admin@sekolah.sch.id"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200">
                    </div>
                    @error('email')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-bold text-navy-900">Kata Sandi</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-12 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200">
                        <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-navy-900" aria-label="Tampilkan kata sandi">
                            <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            <svg id="eye-closed" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="captcha" class="mb-1.5 block text-sm font-bold text-navy-900">Verifikasi: berapa hasil <span id="captcha-question" class="font-mono text-base">{{ $captchaQuestion ?? '' }} = ?</span></label>
                    <div class="flex gap-2">
                        <input type="number" name="captcha" id="captcha" required placeholder="Jawaban angka"
                               class="grow rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200">
                        <button type="button" id="reload-captcha" title="Ganti soal baru" aria-label="Ganti soal captcha baru"
                                class="flex shrink-0 items-center gap-1.5 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-navy-900 transition hover:bg-slate-100">
                            <svg id="reload-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            Baru
                        </button>
                    </div>
                    @error('captcha')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <label class="flex w-fit cursor-pointer items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="h-4 w-4 rounded accent-emerald-600">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" class="group mt-1 flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-navy-900 to-emerald-700 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg transition hover:brightness-110">
                    Masuk ke Dashboard
                    <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </button>

                <a href="{{ route('home') }}" class="text-center text-sm font-semibold text-slate-500 transition hover:text-navy-900">&larr; Kembali ke situs</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            document.getElementById('eye-open')?.classList.toggle('hidden', show);
            document.getElementById('eye-closed')?.classList.toggle('hidden', !show);
            this.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });

        document.getElementById('reload-captcha')?.addEventListener('click', async function () {
            const icon = document.getElementById('reload-icon');
            const label = document.getElementById('captcha-question');
            const input = document.getElementById('captcha');
            icon?.classList.add('animate-spin');

            try {
                const response = await fetch("{{ route('captcha.refresh', 'login') }}", { headers: { 'Accept': 'application/json' } });
                const data = await response.json();
                label.textContent = data.question + ' = ?';
                input.value = '';
                input.focus();
            } catch (e) {
                label.textContent = 'gagal memuat, coba lagi';
            } finally {
                icon?.classList.remove('animate-spin');
            }
        });
    </script>
</body>
</html>

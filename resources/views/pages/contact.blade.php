@extends('layouts.app')

@section('title', 'Kontak — '.($settings['school.name'] ?? ''))

@section('description', 'Hubungi SMP Negeri Satu Atap I Sidamulih: alamat, telepon, email, dan formulir pesan.')

@section('content')
<section class="bg-navy-950">
    <div class="mx-auto max-w-7xl bg-gradient-to-br from-navy-950 via-navy-800 to-emerald-800 px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Hubungi Kami</p>
        <h1 class="mt-2 font-serif text-3xl font-bold text-white sm:text-4xl">Kontak Sekolah</h1>
        <p class="mt-2 text-slate-200">Ada pertanyaan seputar pendaftaran, akademik, atau layanan lainnya? Silakan hubungi kami.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-5">
        <div class="flex flex-col gap-4 lg:col-span-2">
            <div class="rounded-3xl bg-gradient-to-br from-navy-800 to-navy-950 p-7 text-white shadow">
                <h2 class="font-extrabold text-amber-300">Alamat</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-200">{{ $settings['school.address'] ?? '' }}, {{ $settings['school.village'] ?? '' }}, Kec. {{ $settings['school.district'] ?? '' }}, Kab. {{ $settings['school.regency'] ?? '' }}, {{ $settings['school.province'] ?? '' }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-700 p-7 text-white shadow">
                <h2 class="font-extrabold text-amber-200">Telepon / Email</h2>
                <p class="mt-2 text-sm text-emerald-50">{{ $settings['school.phone'] ?? '' }}</p>
                <p class="mt-1 text-sm text-emerald-50">{{ $settings['school.email'] ?? '' }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-amber-400 to-orange-500 p-7 text-navy-950 shadow">
                <h2 class="font-extrabold">Jam Layanan</h2>
                <p class="mt-2 text-sm font-medium">Senin – Jumat: 07.00 – 15.00 WIB<br>Sabtu: 07.00 – 11.00 WIB</p>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100 lg:col-span-3">
            <x-section-heading eyebrow="Formulir" align="left">Kirim Pesan</x-section-heading>

            <form action="{{ route('contact.store') }}" method="POST" class="mt-8 flex flex-col gap-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-bold text-navy-900">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                        @error('name')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contact" class="mb-1.5 block text-sm font-bold text-navy-900">Email / No. HP</label>
                        <input type="text" name="contact" id="contact" value="{{ old('contact') }}" required
                               class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                        @error('contact')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="subject" class="mb-1.5 block text-sm font-bold text-navy-900">Subjek (opsional)</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    @error('subject')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="message" class="mb-1.5 block text-sm font-bold text-navy-900">Pesan</label>
                    <textarea name="message" id="message" rows="5" required
                              class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="captcha" class="mb-1.5 block text-sm font-bold text-navy-900">Verifikasi: berapa hasil <span class="font-mono text-base">{{ $captchaQuestion ?? '' }} = ?</span></label>
                    <input type="number" name="captcha" id="captcha" required placeholder="Jawaban angka"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 sm:max-w-xs">
                    @error('captcha')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <button type="submit" class="rounded-2xl bg-navy-900 px-8 py-3.5 text-sm font-extrabold text-white shadow transition hover:bg-navy-800">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

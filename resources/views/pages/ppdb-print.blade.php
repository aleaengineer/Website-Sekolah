<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Pendaftaran {{ $registration->registration_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
    <main class="mx-auto max-w-2xl px-4 py-10">
        <div class="rounded-3xl bg-white p-8 shadow ring-1 ring-slate-200 sm:p-10">
            <div class="border-b-2 border-navy-900 pb-5 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Bukti Pendaftaran Peserta Didik Baru</p>
                <h1 class="mt-1 font-serif text-xl font-bold text-navy-950 sm:text-2xl">SMP Negeri Satu Atap I Sidamulih</h1>
                <p class="mt-1 text-xs text-slate-500">Jl. Karanganyar No.127 Kalijati, Sidamulih, Pangandaran &bull; NPSN 20253310</p>
            </div>

            <div class="mt-6 rounded-2xl bg-slate-50 p-5 text-center ring-1 ring-slate-200">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Nomor Pendaftaran</p>
                <p class="mt-1 font-mono text-2xl font-extrabold tracking-wider text-navy-900">{{ $registration->registration_number }}</p>
            </div>

            <dl class="mt-6 grid gap-x-8 gap-y-4 text-sm sm:grid-cols-2">
                @php
                    $rows = [
                        'Nama Lengkap' => $registration->student_name,
                        'Tempat, Tanggal Lahir' => $registration->birth_place.', '.$registration->birth_date->translatedFormat('d F Y'),
                        'Jenis Kelamin' => $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                        'Asal Sekolah' => $registration->previous_school,
                        'Jalur Pendaftaran' => ucfirst($registration->jalur),
                        'Nama Orang Tua/Wali' => $registration->parent_name,
                        'No. HP Orang Tua/Wali' => $registration->parent_phone,
                        'Waktu Mendaftar' => $registration->created_at->translatedFormat('d F Y H:i'),
                    ];
                @endphp
                @foreach ($rows as $label => $value)
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $label }}</dt>
                        <dd class="mt-0.5 font-semibold text-navy-900">{{ $value }}</dd>
                    </div>
                @endforeach
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</dt>
                    <dd class="mt-0.5 font-semibold text-navy-900">{{ $registration->address }}</dd>
                </div>
            </dl>

            <p class="mt-6 rounded-2xl bg-amber-50 p-4 text-xs leading-relaxed text-amber-900 ring-1 ring-amber-200">
                Simpan bukti ini dan bawa saat verifikasi berkas ke sekolah beserta fotokopi KK, akta kelahiran, dan rapor/SKHU terakhir.
            </p>

            <div class="mt-8 flex justify-end">
                <div class="text-center text-sm">
                    <p class="text-slate-500">Sidamulih, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="mt-1 font-bold text-navy-900">Panitia PPDB</p>
                    <div class="h-16"></div>
                    <p class="border-t border-slate-300 pt-1 font-semibold">( .............................. )</p>
                </div>
            </div>

            <div class="no-print mt-8 flex gap-3">
                <button type="button" onclick="window.print()" class="grow rounded-2xl bg-navy-900 px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-navy-800">Cetak / Simpan PDF</button>
                <a href="{{ route('ppdb.check', ['nomor' => $registration->registration_number]) }}" class="rounded-2xl border border-slate-200 px-6 py-3.5 text-sm font-bold text-navy-900 transition hover:bg-slate-50">Kembali</a>
            </div>
        </div>
    </main>
</body>
</html>

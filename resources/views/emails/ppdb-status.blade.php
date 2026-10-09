<p>Yth. Bapak/Ibu {{ $registration->parent_name }},</p>

<p>Status pendaftaran PPDB berikut telah diperbarui menjadi <strong>{{ $statusLabel }}</strong>:</p>

<ul>
    <li>Nomor pendaftaran: <strong>{{ $registration->registration_number }}</strong></li>
    <li>Calon peserta didik: <strong>{{ $registration->student_name }}</strong></li>
    <li>Jalur: {{ ucfirst($registration->jalur) }}</li>
    <li>Gelombang: {{ $registration->wave?->name ?? '—' }}</li>
</ul>

@if ($registration->verification_note)
    <p>Catatan panitia: {{ $registration->verification_note }}</p>
@endif

<p>Cek status dan cetak bukti pendaftaran melalui tautan berikut:</p>

<p><a href="{{ route('ppdb.check', ['nomor' => $registration->registration_number]) }}">{{ route('ppdb.check', ['nomor' => $registration->registration_number]) }}</a></p>

<p>Hormat kami,<br>Panitia PPDB {{ config('app.name') }}</p>

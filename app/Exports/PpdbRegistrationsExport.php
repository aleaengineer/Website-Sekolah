<?php

namespace App\Exports;

use App\Models\PpdbRegistration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PpdbRegistrationsExport implements FromCollection, WithHeadings
{
    public function __construct(
        private readonly ?string $status = null,
        private readonly ?int $waveId = null,
    ) {}

    /**
     * @return array<string>
     */
    public function headings(): array
    {
        return [
            'Nomor Pendaftaran',
            'Nama Calon Peserta Didik',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Asal Sekolah',
            'Nama Orang Tua/Wali',
            'No. HP Orang Tua/Wali',
            'Alamat',
            'Jalur',
            'Gelombang',
            'Berkas KK',
            'Berkas Akta',
            'Berkas Rapor',
            'Foto',
            'Status',
            'Catatan Verifikasi',
            'Waktu Mendaftar',
        ];
    }

    public function collection(): Collection
    {
        return PpdbRegistration::with('wave')->latest()
            ->when($this->status, fn ($query, $status) => $query->where('status', $status))
            ->when($this->waveId, fn ($query, $waveId) => $query->where('ppdb_wave_id', $waveId))
            ->get()
            ->map(fn (PpdbRegistration $registration) => [
                $registration->registration_number,
                $registration->student_name,
                $registration->birth_place,
                $registration->birth_date->format('d-m-Y'),
                $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                $registration->previous_school,
                $registration->parent_name,
                $registration->parent_phone,
                $registration->address,
                ucfirst($registration->jalur),
                $registration->wave?->name ?? '—',
                $this->documentLabel($registration->kk_file, $registration->kk_verified),
                $this->documentLabel($registration->akta_file, $registration->akta_verified),
                $this->documentLabel($registration->rapor_file, $registration->rapor_verified),
                $this->documentLabel($registration->photo, $registration->photo_verified),
                $registration->status,
                $registration->verification_note ?? '—',
                $registration->created_at->format('d-m-Y H:i'),
            ]);
    }

    private function documentLabel(?string $path, bool $verified): string
    {
        if (! $path) {
            return 'Belum diunggah';
        }

        return $verified ? 'Ada (terverifikasi)' : 'Ada (belum verifikasi)';
    }
}

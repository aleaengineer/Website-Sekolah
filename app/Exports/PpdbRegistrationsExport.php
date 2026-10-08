<?php

namespace App\Exports;

use App\Models\PpdbRegistration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PpdbRegistrationsExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly ?string $status = null) {}

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
            'Status',
            'Waktu Mendaftar',
        ];
    }

    public function collection(): Collection
    {
        return PpdbRegistration::latest()
            ->when($this->status, fn ($query, $status) => $query->where('status', $status))
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
                $registration->status,
                $registration->created_at->format('d-m-Y H:i'),
            ]);
    }
}

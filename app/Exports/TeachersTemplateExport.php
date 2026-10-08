<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersTemplateExport implements FromArray, WithHeadings
{
    /**
     * @return array<string>
     */
    public function headings(): array
    {
        return ['nama', 'jabatan', 'mapel', 'urutan', 'aktif'];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        return [
            ['Contoh Nama Guru', 'Guru', 'Matematika', 1, 1],
        ];
    }
}

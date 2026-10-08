<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    /**
     * @return array<string>
     */
    public function headings(): array
    {
        return ['nama', 'jabatan', 'mapel', 'urutan', 'aktif'];
    }

    public function collection(): Collection
    {
        return Teacher::ordered()->get()->map(fn (Teacher $teacher) => [
            $teacher->name,
            $teacher->position,
            $teacher->subject,
            $teacher->sort_order,
            $teacher->is_active ? 1 : 0,
        ]);
    }
}

<?php

namespace App\Imports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements SkipsEmptyRows, SkipsOnFailure, ToCollection, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    public int $imported = 0;

    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $name = trim((string) ($row['nama'] ?? ''));

            if ($name === '' || Teacher::where('name', $name)->exists()) {
                $this->skipped++;

                continue;
            }

            Teacher::create([
                'name' => $name,
                'position' => trim((string) ($row['jabatan'] ?? 'Guru')) ?: 'Guru',
                'subject' => ($subject = trim((string) ($row['mapel'] ?? ''))) !== '' ? $subject : null,
                'sort_order' => (int) ($row['urutan'] ?? 0),
                'is_active' => $this->toBoolean($row['aktif'] ?? 1),
            ]);

            $this->imported++;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            '*.nama' => ['required', 'string', 'max:100'],
            '*.jabatan' => ['nullable', 'string', 'max:100'],
            '*.mapel' => ['nullable', 'string', 'max:100'],
            '*.urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    private function toBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(mb_strtolower(trim((string) $value)), ['1', 'ya', 'y', 'aktif', 'true'], true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PpdbRegistration extends Model
{
    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DIVERIFIKASI = 'diverifikasi';

    public const STATUS_DITERIMA = 'diterima';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'registration_number',
        'student_name',
        'birth_place',
        'birth_date',
        'gender',
        'previous_school',
        'parent_name',
        'parent_phone',
        'address',
        'jalur',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_MENUNGGU,
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    /**
     * Generate a sequential registration number for the current year.
     */
    public static function generateNumber(): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($year) {
            $sequence = static::where('registration_number', 'like', "PPDB-{$year}-%")
                ->lockForUpdate()
                ->count() + 1;

            return sprintf('PPDB-%s-%04d', $year, $sequence);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PpdbRegistration extends Model
{
    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DIVERIFIKASI = 'diverifikasi';

    public const STATUS_DITERIMA = 'diterima';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_CADANGAN = 'cadangan';

    public const STATUSES = [
        self::STATUS_MENUNGGU => 'Menunggu',
        self::STATUS_DIVERIFIKASI => 'Diverifikasi',
        self::STATUS_DITERIMA => 'Diterima',
        self::STATUS_DITOLAK => 'Ditolak',
        self::STATUS_CADANGAN => 'Cadangan',
    ];

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
        'ppdb_wave_id',
        'status',
        'kk_file',
        'akta_file',
        'rapor_file',
        'photo',
        'kk_verified',
        'akta_verified',
        'rapor_verified',
        'photo_verified',
        'verification_note',
    ];

    protected $attributes = [
        'status' => self::STATUS_MENUNGGU,
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'kk_verified' => 'boolean',
            'akta_verified' => 'boolean',
            'rapor_verified' => 'boolean',
            'photo_verified' => 'boolean',
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

    /**
     * @return BelongsTo<PpdbWave, $this>
     */
    public function wave(): BelongsTo
    {
        return $this->belongsTo(PpdbWave::class, 'ppdb_wave_id');
    }
}

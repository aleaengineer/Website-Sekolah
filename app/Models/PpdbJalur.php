<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbJalur extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * @return HasMany<PpdbRegistration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(PpdbRegistration::class, 'jalur', 'slug');
    }

    /**
     * Canonical tracks used for seeding and tests.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaults(): array
    {
        return [
            ['name' => 'Zonasi', 'slug' => 'zonasi', 'description' => 'Berdasarkan jarak domisili ke sekolah.', 'sort_order' => 1],
            ['name' => 'Afirmasi', 'slug' => 'afirmasi', 'description' => 'Untuk keluarga kurang mampu dan disabilitas.', 'sort_order' => 2],
            ['name' => 'Prestasi', 'slug' => 'prestasi', 'description' => 'Berdasarkan prestasi akademik dan non-akademik.', 'sort_order' => 3],
            ['name' => 'Perpindahan Tugas Orang Tua', 'slug' => 'mutasi', 'description' => 'Untuk anak yang orang tuanya pindah tugas.', 'sort_order' => 4],
        ];
    }
}

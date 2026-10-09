<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    public const CATEGORIES = [
        'kegiatan' => 'Kegiatan',
        'ujian' => 'Ujian / Asesmen',
        'libur' => 'Libur',
        'penerimaan' => 'Penerimaan / PPDB',
    ];

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'category',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('start_date');
    }
}

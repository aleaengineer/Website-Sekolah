<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StudentStatistic extends Model
{
    protected $fillable = [
        'year',
        'male_students',
        'female_students',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'male_students' => 'integer',
            'female_students' => 'integer',
        ];
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->male_students + $this->female_students,
        );
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('year');
    }
}

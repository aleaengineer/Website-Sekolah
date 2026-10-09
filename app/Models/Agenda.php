<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'start_at',
        'end_at',
        'cover_image',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    #[Scope]
    protected function upcoming(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('start_at', '>=', now())
            ->orderBy('start_at');
    }

    #[Scope]
    protected function recent(Builder $query): Builder
    {
        return $query->orderByDesc('start_at');
    }
}

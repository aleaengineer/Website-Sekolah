<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public const CATEGORIES = [
        'umum' => 'Umum',
        'formulir' => 'Formulir',
        'panduan' => 'Panduan',
        'regulasi' => 'Regulasi',
    ];

    protected $fillable = [
        'title',
        'description',
        'file',
        'category',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('updated_at');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'contact',
        'subject',
        'message',
    ];

    protected $attributes = [
        'is_read' => false,
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    #[Scope]
    protected function unread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }
}

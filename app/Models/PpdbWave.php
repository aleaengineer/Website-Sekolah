<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbWave extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'quota',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'quota' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('start_date');
    }

    #[Scope]
    protected function open(Builder $query): Builder
    {
        $today = today()->toDateString();

        return $query->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->orderBy('start_date');
    }

    /**
     * @return HasMany<PpdbRegistration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(PpdbRegistration::class);
    }

    public function isOpen(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = today()->toDateString();

        return $this->start_date->toDateString() <= $today && $today <= $this->end_date->toDateString();
    }

    public function remainingQuota(): int
    {
        return max(0, $this->quota - $this->registrations()->count());
    }

    public function isFull(): bool
    {
        return $this->remainingQuota() <= 0;
    }
}

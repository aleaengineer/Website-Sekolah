<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a stored setting value by key.
     */
    public static function value(string $key, ?string $default = null): ?string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Get an embeddable Google Maps URL.
     *
     * Plain share URLs (e.g. https://www.google.com/maps?q=...) refuse to load
     * inside an iframe, so the embed flag is appended automatically.
     */
    public static function mapsEmbedUrl(): ?string
    {
        $url = static::value('school.maps_embed');

        if (! $url) {
            return null;
        }

        if (str_contains($url, 'output=embed') || str_contains($url, '/maps/embed')) {
            return $url;
        }

        if (preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $url, $matches) === 1) {
            return "https://maps.google.com/maps?q={$matches[1]},{$matches[2]}&output=embed";
        }

        if (preg_match('/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/', $url, $matches) === 1) {
            return "https://maps.google.com/maps?q={$matches[1]},{$matches[2]}&output=embed";
        }

        if (str_contains($url, 'google.com/maps') || str_contains($url, 'maps.google.')) {
            return $url.(str_contains($url, '?') ? '&' : '?').'output=embed';
        }

        return $url;
    }
}

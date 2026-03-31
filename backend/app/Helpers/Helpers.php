<?php

namespace App\Helpers;

use App\Models\Manga;

class Helpers
{
    public static function formatNumber(float $number): int|float
    {
        return fmod($number, 1) === 0.0 ? (int) $number : $number;
    }

    public static function generateUniqueSlug(string $base): string
    {
        $existing = Manga::where('slug', 'LIKE', "{$base}%")
            ->pluck('slug')
            ->toArray();

        if (!in_array($base, $existing)) {
            return $base;
        }

        $count = 1;
        while (in_array("{$base}-{$count}", $existing)) {
            $count++;
        }

        return "{$base}-{$count}";
    }
}
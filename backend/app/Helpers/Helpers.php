<?php

namespace App\Helpers;

class Helper
{
    public static function formatNumber(float $number): int|float
    {
        return fmod($number, 1) === 0.0 ? (int) $number : $number;
    }
}
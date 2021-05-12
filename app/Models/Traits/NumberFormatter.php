<?php

namespace App\Models\Traits;

trait NumberFormatter
{
    /**
     * @param float|null $value
     * @return float|null
     */
    public function round(?float $value): ?float
    {
        if (!$value) {
            return null;
        }
        return round($value, 1);
    }

    /**
     * @param float|null $value
     * @return string|null
     */
    public function bigIntFormat(?float $value): ?string
    {
        if (!$value) {
            return null;
        }

        return number_format($value, 0, '.', ' ');
    }
}

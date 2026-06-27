<?php

declare(strict_types=1);

namespace App\Services;

class NameNormalizer
{
    public function normalize(string $value): string
    {
        return trim(mb_strtolower($value));
    }
}

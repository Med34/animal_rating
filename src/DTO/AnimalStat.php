<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class AnimalStat
{
    public function __construct(
        public string $animalName,
        public int $citations,
        public float $avgScore,
    ) {
    }
}

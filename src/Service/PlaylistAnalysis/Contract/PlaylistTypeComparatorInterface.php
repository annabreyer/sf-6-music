<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\PlaylistTypeComparison;

interface PlaylistTypeComparatorInterface
{
    public function compareTypes(string $typeA, string $typeB): PlaylistTypeComparison;
}

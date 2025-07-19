<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\SongFrequencyData;

interface SongFrequencyCalculatorInterface
{
    /** @return SongFrequencyData[] */
    public function calculateSongFrequencies(array $playlists): array;
}

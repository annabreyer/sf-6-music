<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\SongFrequencyData;

interface CommonSongAnalyzerInterface
{
    /** @return SongFrequencyData[] */
    public function findCommonSeasonalSongs(string $playlistType): array;

    /** @return SongFrequencyData[] */
    public function findUniversalSongs(string $playlistType): array;
}

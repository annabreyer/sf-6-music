<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\ArtistFrequencyData;

interface ArtistAnalyzerInterface
{
    /** @return ArtistFrequencyData[] */
    public function getMostFrequentArtists(array $playlists, int $limit = 10): array;
}

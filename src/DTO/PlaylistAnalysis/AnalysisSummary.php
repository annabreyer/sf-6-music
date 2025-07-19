<?php declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

final readonly class AnalysisSummary
{
    public function __construct(
        public int $totalPlaylistTypes,
        public int $totalPlaylists,
        public int $totalUniqueSongs,
        public float $averageSongsPerType,
        public float $averagePlaylistsPerType
    ) {
    }

    public function getAverageSongsPerPlaylist(): float
    {
        return $this->totalPlaylists > 0 ? round($this->totalUniqueSongs / $this->totalPlaylists, 1) : 0.0;
    }
}

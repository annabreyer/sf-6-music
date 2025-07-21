<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;

class CommonSongAnalyzer
{
    public function __construct(
        private readonly PlaylistDataProviderInterface $dataProvider,
        private readonly SongFrequencyCalculator $frequencyCalculator
    ) {
    }

    public function findCommonSeasonalSongs(string $playlistType): array
    {
        $playlists = $this->dataProvider->getPlaylistsByType($playlistType);

        if (count($playlists) <= 1) {
            return [];
        }

        $frequencies = $this->frequencyCalculator->calculateSongFrequencies($playlists);
        $commonSongs = $this->frequencyCalculator->getCommonSongs($frequencies);

        return $this->frequencyCalculator->sortByFrequency($commonSongs);
    }

    public function findUniversalSongs(string $playlistType): array
    {
        $playlists = $this->dataProvider->getPlaylistsByType($playlistType);
        $totalPlaylists = count($playlists);

        if ($totalPlaylists <= 1) {
            return [];
        }

        $frequencies = $this->frequencyCalculator->calculateSongFrequencies($playlists);
        return $this->frequencyCalculator->getUniversalSongs($frequencies, $totalPlaylists);
    }
}

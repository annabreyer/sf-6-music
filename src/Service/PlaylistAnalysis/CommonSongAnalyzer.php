<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\Service\PlaylistAnalysis\Contract\CommonSongAnalyzerInterface;
use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;
use App\Service\PlaylistAnalysis\Contract\SongFrequencyCalculatorInterface;

class CommonSongAnalyzer implements CommonSongAnalyzerInterface
{
    public function __construct(
        private readonly PlaylistDataProviderInterface $dataProvider,
        private readonly SongFrequencyCalculatorInterface $frequencyCalculator
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

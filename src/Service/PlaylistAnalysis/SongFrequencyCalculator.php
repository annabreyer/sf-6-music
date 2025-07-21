<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;


use App\DTO\PlaylistAnalysis\SongFrequencyData;

class SongFrequencyCalculator
{
    public function calculateSongFrequencies(array $playlists): array
    {
        $songFrequency = [];

        foreach ($playlists as $playlist) {
            foreach ($playlist->getSongs() as $song) {
                $songId = $song->getId();
                if (!isset($songFrequency[$songId])) {
                    $songFrequency[$songId] = [
                        'song' => $song,
                        'count' => 0,
                        'playlists' => []
                    ];
                }
                $songFrequency[$songId]['count']++;
                $songFrequency[$songId]['playlists'][] = $playlist->getName();
            }
        }

        // Convert to DTOs
        return array_map(
            fn($data) => new SongFrequencyData(
                $data['song'],
                $data['count'],
                $data['playlists']
            ),
            $songFrequency
        );
    }

    /** @return SongFrequencyData[] */
    public function getCommonSongs(array $songFrequencies): array
    {
        return array_filter($songFrequencies, fn(SongFrequencyData $data) => $data->appearsInMultiplePlaylists());
    }

    /** @return SongFrequencyData[] */
    public function getUniversalSongs(array $songFrequencies, int $totalPlaylists): array
    {
        return array_filter($songFrequencies, fn(SongFrequencyData $data) => $data->appearsInAllPlaylists($totalPlaylists));
    }

    /** @return SongFrequencyData[] */
    public function sortByFrequency(array $songFrequencies): array
    {
        usort($songFrequencies, fn(SongFrequencyData $a, SongFrequencyData $b) => $b->count <=> $a->count);
        return $songFrequencies;
    }
}
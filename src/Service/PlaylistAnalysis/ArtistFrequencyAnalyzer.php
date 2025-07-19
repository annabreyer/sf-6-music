<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\DTO\PlaylistAnalysis\ArtistFrequencyData;
use App\Service\PlaylistAnalysis\Contract\ArtistAnalyzerInterface;

class ArtistFrequencyAnalyzer implements ArtistAnalyzerInterface
{
    public function getMostFrequentArtists(array $playlists, int $limit = 10): array
    {
        $artistFrequency = [];

        foreach ($playlists as $playlist) {
            foreach ($playlist->getSongs() as $song) {
                $artist = $song->getArtist();
                if (!$artist) continue;

                $artistId = $artist->getId();
                if (!isset($artistFrequency[$artistId])) {
                    $artistFrequency[$artistId] = [
                        'artist' => $artist,
                        'song_count' => 0,
                        'playlist_count' => 0,
                        'playlists' => []
                    ];
                }

                $artistFrequency[$artistId]['song_count']++;

                $playlistName = $playlist->getName();
                if (!in_array($playlistName, $artistFrequency[$artistId]['playlists'])) {
                    $artistFrequency[$artistId]['playlists'][] = $playlistName;
                    $artistFrequency[$artistId]['playlist_count']++;
                }
            }
        }

        uasort($artistFrequency, fn($a, $b) => $b['song_count'] <=> $a['song_count']);

        $result = array_slice(array_values($artistFrequency), 0, $limit);

        return array_map(
            fn($data) => new ArtistFrequencyData(
                $data['artist'],
                $data['song_count'],
                $data['playlist_count'],
                $data['playlists']
            ),
            $result
        );
    }
}

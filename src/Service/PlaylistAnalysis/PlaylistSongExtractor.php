<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\Service\PlaylistAnalysis\Contract\PlaylistSongExtractorInterface;

class PlaylistSongExtractor implements PlaylistSongExtractorInterface
{
    public function getAllSongsFromPlaylists(array $playlists): array
    {
        $songs = [];
        foreach ($playlists as $playlist) {
            foreach ($playlist->getSongs() as $song) {
                $songs[$song->getId()] = $song;
            }
        }
        return $songs;
    }

    public function extractSongIds(array $songsCollection): array
    {
        return array_keys($songsCollection);
    }
}

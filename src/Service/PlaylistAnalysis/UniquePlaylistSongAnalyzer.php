<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\DTO\PlaylistAnalysis\PlaylistUniqueSongs;
use App\Entity\Playlist;
use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;

class UniquePlaylistSongAnalyzer
{
    public function __construct(
        private readonly PlaylistDataProviderInterface $dataProvider,
    ) {}

    public function findUniqueSongs(Playlist $playlist): PlaylistUniqueSongs
    {
        $uniqueSongs = [];

        foreach ($playlist->getTypes() as $type) {
            $otherPlaylists = $this->getOtherPlaylistsOfType($playlist, $type->getType());

            if (empty($otherPlaylists)) {
                $uniqueSongs[$type->getType()] = $playlist->getSongs()->toArray();
                continue;
            }

            $otherSongIds                  = $this->extractSongIdsFromPlaylists($otherPlaylists);
            $uniqueToType                  = $this->findSongsNotInCollection($playlist, $otherSongIds);
            $uniqueSongs[$type->getType()] = $uniqueToType;
        }

        return new PlaylistUniqueSongs($uniqueSongs);
    }

    private function getOtherPlaylistsOfType(Playlist $currentPlaylist, string $type): array
    {
        $allPlaylists = $this->dataProvider->getPlaylistsByType($type);

        return array_filter($allPlaylists, function (Playlist $p) use ($currentPlaylist) {
            return $p->getId() !== $currentPlaylist->getId();
        });
    }

    private function extractSongIdsFromPlaylists(array $playlists): array
    {
        $songIds = [];
        foreach ($playlists as $playlist) {
            foreach ($playlist->getSongs() as $song) {
                $songIds[] = $song->getId();
            }
        }

        return array_unique($songIds);
    }

    private function findSongsNotInCollection(Playlist $playlist, array $excludedSongIds): array
    {
        $uniqueSongs = [];
        foreach ($playlist->getSongs() as $song) {
            if (!in_array($song->getId(), $excludedSongIds)) {
                $uniqueSongs[] = $song;
            }
        }

        return $uniqueSongs;
    }
}
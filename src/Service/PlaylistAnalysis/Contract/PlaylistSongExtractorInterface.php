<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\Entity\Song;

interface PlaylistSongExtractorInterface
{
    /** @return Song[] */
    public function getAllSongsFromPlaylists(array $playlists): array;

    /** @return int[] */
    public function extractSongIds(array $songsCollection): array;
}
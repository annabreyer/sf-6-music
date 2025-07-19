<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\Entity\Playlist;

interface PlaylistDataProviderInterface
{
    /** @return Playlist[] */
    public function getPlaylistsByType(string $type): array;

    /** @return string[] */
    public function getAllPlaylistTypes(): array;
}

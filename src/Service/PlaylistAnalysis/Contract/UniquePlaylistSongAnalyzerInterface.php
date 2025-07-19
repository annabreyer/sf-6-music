<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\PlaylistUniqueSongs;
use App\Entity\Playlist;

interface UniquePlaylistSongAnalyzerInterface
{
    public function findUniqueSongs(Playlist $playlist): PlaylistUniqueSongs;
}
<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\Entity\Playlist;

interface PlaylistSimilarityCalculatorInterface
{
    public function calculateSimilarity(Playlist $playlistA, Playlist $playlistB): float;
    public function calculateTypeSimilarity(array $songIdsA, array $songIdsB): float;
}
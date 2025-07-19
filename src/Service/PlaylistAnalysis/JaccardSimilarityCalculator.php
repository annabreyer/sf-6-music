<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Service\PlaylistAnalysis\Contract\PlaylistSimilarityCalculatorInterface;

class JaccardSimilarityCalculator implements PlaylistSimilarityCalculatorInterface
{
    public function calculateSimilarity(Playlist $playlistA, Playlist $playlistB): float
    {
        $songsA = $playlistA->getSongs()->toArray();
        $songsB = $playlistB->getSongs()->toArray();

        if (empty($songsA) && empty($songsB)) {
            return 100.0;
        }

        if (empty($songsA) || empty($songsB)) {
            return 0.0;
        }

        $idsA = array_map(fn(Song $song) => $song->getId(), $songsA);
        $idsB = array_map(fn(Song $song) => $song->getId(), $songsB);

        $intersection = array_intersect($idsA, $idsB);
        $union = array_unique(array_merge($idsA, $idsB));

        return (count($intersection) / count($union)) * 100;
    }

    public function calculateTypeSimilarity(array $songIdsA, array $songIdsB): float
    {
        if (empty($songIdsA) && empty($songIdsB)) {
            return 100.0;
        }

        if (empty($songIdsA) || empty($songIdsB)) {
            return 0.0;
        }

        $intersection = array_intersect($songIdsA, $songIdsB);
        $union = array_unique(array_merge($songIdsA, $songIdsB));

        return (count($intersection) / count($union)) * 100;
    }
}

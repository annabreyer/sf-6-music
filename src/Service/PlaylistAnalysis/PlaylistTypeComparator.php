<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\DTO\PlaylistAnalysis\PlaylistTypeComparison;
use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;

class PlaylistTypeComparator
{
    public function __construct(
        private readonly PlaylistDataProviderInterface $dataProvider,
        private readonly PlaylistSongExtractor $songExtractor,
        private readonly JaccardSimilarityCalculator $similarityCalculator
    ) {
    }

    public function compareTypes(string $typeA, string $typeB): PlaylistTypeComparison
    {
        $playlistsA = $this->dataProvider->getPlaylistsByType($typeA);
        $playlistsB = $this->dataProvider->getPlaylistsByType($typeB);

        $songsA = $this->songExtractor->getAllSongsFromPlaylists($playlistsA);
        $songsB = $this->songExtractor->getAllSongsFromPlaylists($playlistsB);

        $songIdsA = $this->songExtractor->extractSongIds($songsA);
        $songIdsB = $this->songExtractor->extractSongIds($songsB);

        $commonSongIds = array_intersect($songIdsA, $songIdsB);
        $onlyInA = array_diff($songIdsA, $songIdsB);
        $onlyInB = array_diff($songIdsB, $songIdsA);

        return new PlaylistTypeComparison(
            typeA: $typeA,
            typeB: $typeB,
            playlistsInA: count($playlistsA),
            playlistsInB: count($playlistsB),
            totalSongsInA: count($songsA),
            totalSongsInB: count($songsB),
            commonSongs: array_map(fn($id) => $songsA[$id] ?? $songsB[$id], $commonSongIds),
            uniqueToA: array_map(fn($id) => $songsA[$id], $onlyInA),
            uniqueToB: array_map(fn($id) => $songsB[$id], $onlyInB),
            similarityPercentage: $this->similarityCalculator->calculateTypeSimilarity($songIdsA, $songIdsB)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\DTO\PlaylistAnalysis\CompleteAnalysis;
use App\DTO\PlaylistAnalysis\PlaylistTypeComparison;
use App\DTO\PlaylistAnalysis\PlaylistUniqueSongs;
use App\DTO\PlaylistAnalysis\SeasonalPatterns;
use App\DTO\PlaylistAnalysis\SongFrequencyData;
use App\Entity\Playlist;
use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;

class PlaylistAnalysisService
{
    public function __construct(
        private readonly PlaylistDataProviderInterface $dataProvider,
        private readonly CommonSongAnalyzer $commonSongAnalyzer,
        private readonly PlaylistTypeComparator $typeComparator,
        private readonly UniquePlaylistSongAnalyzer $uniqueSongAnalyzer,
        private readonly SeasonalPatternAnalyzer $patternAnalyzer,
        private readonly JaccardSimilarityCalculator $similarityCalculator,
        private readonly AnalysisSummaryGenerator $summaryGenerator
    ) {}

    /** @return SongFrequencyData[] */
    public function findCommonSeasonalSongs(string $playlistType): array
    {
        return $this->commonSongAnalyzer->findCommonSeasonalSongs($playlistType);
    }

    /** @return SongFrequencyData[] */
    public function findSongsInAllPlaylistsOfType(string $playlistType): array
    {
        return $this->commonSongAnalyzer->findUniversalSongs($playlistType);
    }

    public function calculatePlaylistSimilarity(Playlist $playlistA, Playlist $playlistB): float
    {
        return $this->similarityCalculator->calculateSimilarity($playlistA, $playlistB);
    }

    public function findUniqueSongs(Playlist $playlist): PlaylistUniqueSongs
    {
        return $this->uniqueSongAnalyzer->findUniqueSongs($playlist);
    }

    public function comparePlaylistTypes(string $typeA, string $typeB): PlaylistTypeComparison
    {
        return $this->typeComparator->compareTypes($typeA, $typeB);
    }

    public function getSeasonalPatterns(string $playlistType): SeasonalPatterns
    {
        return $this->patternAnalyzer->getSeasonalPatterns($playlistType);
    }

    public function getCompleteAnalysis(): CompleteAnalysis
    {
        $allTypes       = $this->dataProvider->getAllPlaylistTypes();
        $patternsByType = [];

        foreach ($allTypes as $type) {
            $patterns = $this->patternAnalyzer->getSeasonalPatterns($type);
            if ($patterns->hasData()) {
                $patternsByType[$type] = $patterns;
            }
        }

        $typesWithPlaylists = array_keys($patternsByType);
        $comparisons        = [];

        for ($i = 0; $i < count($typesWithPlaylists); $i++) {
            for ($j = $i + 1; $j < count($typesWithPlaylists); $j++) {
                $typeA                               = $typesWithPlaylists[$i];
                $typeB                               = $typesWithPlaylists[$j];
                $comparisons["{$typeA}_vs_{$typeB}"] = $this->typeComparator->compareTypes($typeA, $typeB);
            }
        }

        return new CompleteAnalysis(
            patternsByType: $patternsByType,
            typeComparisons: $comparisons,
            summary: $this->summaryGenerator->generateSummary($patternsByType)
        );
    }
}


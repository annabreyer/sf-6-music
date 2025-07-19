<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\DTO\PlaylistAnalysis\AnalysisSummary;
use App\DTO\PlaylistAnalysis\SeasonalPatterns;
use App\Service\PlaylistAnalysis\Contract\AnalysisSummaryGeneratorInterface;

class AnalysisSummaryGenerator implements AnalysisSummaryGeneratorInterface
{
    public function generateSummary(array $analysisByType): AnalysisSummary
    {
        $totalPlaylists = 0;
        $totalSongs = 0;
        $typeCount = count($analysisByType);

        /** @var SeasonalPatterns $typeData */
        foreach ($analysisByType as $typeData) {
            $totalPlaylists += $typeData->totalPlaylists;
            $totalSongs += $typeData->totalUniqueSongs;
        }

        return new AnalysisSummary(
            totalPlaylistTypes: $typeCount,
            totalPlaylists: $totalPlaylists,
            totalUniqueSongs: $totalSongs,
            averageSongsPerType: $typeCount > 0 ? round($totalSongs / $typeCount, 1) : 0.0,
            averagePlaylistsPerType: $typeCount > 0 ? round($totalPlaylists / $typeCount, 1) : 0.0
        );
    }
}

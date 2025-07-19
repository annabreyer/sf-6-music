<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\SeasonalPatterns;

interface SeasonalPatternAnalyzerInterface
{
    public function getSeasonalPatterns(string $playlistType): SeasonalPatterns;
}

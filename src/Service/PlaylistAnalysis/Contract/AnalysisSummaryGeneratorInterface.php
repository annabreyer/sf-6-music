<?php

namespace App\Service\PlaylistAnalysis\Contract;

use App\DTO\PlaylistAnalysis\AnalysisSummary;

interface AnalysisSummaryGeneratorInterface
{
    public function generateSummary(array $analysisByType): AnalysisSummary;
}

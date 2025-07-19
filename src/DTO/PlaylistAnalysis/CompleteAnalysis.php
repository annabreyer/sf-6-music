<?php declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

final readonly class CompleteAnalysis
{
    public function __construct(
        /** @var array<string, SeasonalPatterns> */
        public array $patternsByType,
        /** @var array<string, PlaylistTypeComparison> */
        public array $typeComparisons,
        public AnalysisSummary $summary
    ) {
    }

    public function getPatternsForType(string $type): ?SeasonalPatterns
    {
        return $this->patternsByType[$type] ?? null;
    }

    public function getComparisonBetween(string $typeA, string $typeB): ?PlaylistTypeComparison
    {
        $key1 = "{$typeA}_vs_{$typeB}";
        $key2 = "{$typeB}_vs_{$typeA}";

        return $this->typeComparisons[$key1] ?? $this->typeComparisons[$key2] ?? null;
    }

    public function getAllAnalyzedTypes(): array
    {
        return array_keys($this->patternsByType);
    }
}

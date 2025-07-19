<?php declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

use App\Entity\Song;

final readonly class PlaylistTypeComparison
{
    public function __construct(
        public string $typeA,
        public string $typeB,
        public int $playlistsInA,
        public int $playlistsInB,
        public int $totalSongsInA,
        public int $totalSongsInB,
        /** @var Song[] */
        public array $commonSongs,
        /** @var Song[] */
        public array $uniqueToA,
        /** @var Song[] */
        public array $uniqueToB,
        public float $similarityPercentage
    ) {
    }

    public function getCommonSongCount(): int
    {
        return count($this->commonSongs);
    }

    public function getUniqueToACount(): int
    {
        return count($this->uniqueToA);
    }

    public function getUniqueToBCount(): int
    {
        return count($this->uniqueToB);
    }

    public function isHighlySimilar(float $threshold = 70.0): bool
    {
        return $this->similarityPercentage >= $threshold;
    }
}

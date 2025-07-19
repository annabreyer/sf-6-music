<?php declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

use App\Entity\Song;

final readonly class PlaylistUniqueSongs
{
    public function __construct(
        /** @var array<string, Song[]> */
        public array $uniqueSongsByType
    ) {
    }

    public function getUniqueSongsForType(string $type): array
    {
        return $this->uniqueSongsByType[$type] ?? [];
    }

    public function hasUniqueSongsForType(string $type): bool
    {
        return !empty($this->uniqueSongsByType[$type] ?? []);
    }

    public function getAllTypes(): array
    {
        return array_keys($this->uniqueSongsByType);
    }
}
<?php

declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

use App\Entity\Song;

final readonly class SongFrequencyData
{
    public function __construct(
        public Song $song,
        public int $count,
        /** @var string[] */
        public array $playlistNames
    ) {
    }

    public function appearsInMultiplePlaylists(): bool
    {
        return $this->count > 1;
    }

    public function appearsInAllPlaylists(int $totalPlaylists): bool
    {
        return $this->count === $totalPlaylists;
    }
}

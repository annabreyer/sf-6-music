<?php declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

use App\Entity\Artist;

final readonly class ArtistFrequencyData
{
    public function __construct(
        public Artist $artist,
        public int $songCount,
        public int $playlistCount,
        /** @var string[] */
        public array $playlistNames
    ) {
    }
}

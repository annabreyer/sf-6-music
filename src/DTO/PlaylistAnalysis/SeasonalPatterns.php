<?php declare(strict_types=1);

namespace App\DTO\PlaylistAnalysis;

final readonly class SeasonalPatterns
{
    public function __construct(
        public string $type,
        public int $totalPlaylists,
        public int $totalUniqueSongs,
        /** @var SongFrequencyData[] */
        public array $commonSongs,
        /** @var SongFrequencyData[] */
        public array $universalSongs,
        /** @var ArtistFrequencyData[] */
        public array $topArtists,
        /** @var string[] */
        public array $playlistNames,
        public ?string $message = null
    ) {
    }

    public function hasData(): bool
    {
        return $this->totalPlaylists > 0;
    }

    public function isEmpty(): bool
    {
        return !$this->hasData();
    }

    public function getMostCommonSongs(int $limit = 10): array
    {
        return array_slice($this->commonSongs, 0, $limit);
    }
}

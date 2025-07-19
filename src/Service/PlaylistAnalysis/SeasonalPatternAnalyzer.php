<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\DTO\PlaylistAnalysis\SeasonalPatterns;
use App\Entity\Playlist;
use App\Service\PlaylistAnalysis\Contract\ArtistAnalyzerInterface;
use App\Service\PlaylistAnalysis\Contract\CommonSongAnalyzerInterface;
use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;
use App\Service\PlaylistAnalysis\Contract\PlaylistSongExtractorInterface;
use App\Service\PlaylistAnalysis\Contract\SeasonalPatternAnalyzerInterface;

class SeasonalPatternAnalyzer implements SeasonalPatternAnalyzerInterface
{
    public function __construct(
        private readonly PlaylistDataProviderInterface $dataProvider,
        private readonly CommonSongAnalyzerInterface $commonSongAnalyzer,
        private readonly PlaylistSongExtractorInterface $songExtractor,
        private readonly ArtistAnalyzerInterface $artistAnalyzer
    ) {
    }

    public function getSeasonalPatterns(string $playlistType): SeasonalPatterns
    {
        $playlists = $this->dataProvider->getPlaylistsByType($playlistType);

        if (empty($playlists)) {
            return new SeasonalPatterns(
                type: $playlistType,
                totalPlaylists: 0,
                totalUniqueSongs: 0,
                commonSongs: [],
                universalSongs: [],
                topArtists: [],
                playlistNames: [],
                message: "No playlists found for type: {$playlistType}"
            );
        }

        $commonSongs = $this->commonSongAnalyzer->findCommonSeasonalSongs($playlistType);
        $universalSongs = $this->commonSongAnalyzer->findUniversalSongs($playlistType);
        $topArtists = $this->artistAnalyzer->getMostFrequentArtists($playlists, 5);
        $allSongs = $this->songExtractor->getAllSongsFromPlaylists($playlists);

        return new SeasonalPatterns(
            type: $playlistType,
            totalPlaylists: count($playlists),
            totalUniqueSongs: count($allSongs),
            commonSongs: $commonSongs,
            universalSongs: $universalSongs,
            topArtists: $topArtists,
            playlistNames: array_map(fn(Playlist $p) => $p->getName(), $playlists)
        );
    }
}

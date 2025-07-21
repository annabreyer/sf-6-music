<?php declare(strict_types=1);

namespace App\Controller\API;

use App\Service\PlaylistAnalysis\PlaylistAnalysisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/analysis', name: 'api_analysis_')]
class AnalysisController extends AbstractController
{
    public function __construct(
        private readonly PlaylistAnalysisService $analysisService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/common/{type}', name: 'common', methods: ['GET'])]
    public function apiCommonSongs(string $type): JsonResponse
    {
        $commonSongs = $this->analysisService->findCommonSeasonalSongs($type);

        return $this->json([
            'type' => $type,
            'common_songs' => array_map(function($songData) {
                return [
                    'title' => $songData->song->getTitle(),
                    'artist' => $songData->song->getArtist()?->getName(),
                    'album' => $songData->song->getAlbum()?->getName(),
                    'frequency' => $songData->count,
                    'playlists' => $songData->playlistNames
                ];
            }, $commonSongs)
        ]);
    }

    #[Route('/api/universal/{type}', name: 'universal', methods: ['GET'])]
    public function apiUniversalSongs(string $type): JsonResponse
    {
        $universalSongs = $this->analysisService->findSongsInAllPlaylistsOfType($type);

        return $this->json([
            'type' => $type,
            'universal_songs' => array_map(function($songData) {
                return [
                    'title' => $songData->song->getTitle(),
                    'artist' => $songData->song->getArtist()?->getName(),
                    'album' => $songData->song->getAlbum()?->getName(),
                    'playlists' => $songData->playlistNames
                ];
            }, $universalSongs)
        ]);
    }

    /**
     * API endpoint for playlist type comparison
     */
    #[Route('/api/compare/{typeA}/{typeB}', name: 'compare', methods: ['GET'])]
    public function apiCompareTypes(string $typeA, string $typeB): JsonResponse
    {
        $comparison = $this->analysisService->comparePlaylistTypes($typeA, $typeB);

        return $this->json([
            'type_a' => $comparison->typeA,
            'type_b' => $comparison->typeB,
            'similarity_percentage' => round($comparison->similarityPercentage, 2),
            'common_song_count' => $comparison->getCommonSongCount(),
            'unique_to_a_count' => $comparison->getUniqueToACount(),
            'unique_to_b_count' => $comparison->getUniqueToBCount(),
            'total_songs_a' => $comparison->totalSongsInA,
            'total_songs_b' => $comparison->totalSongsInB
        ]);
    }

    /**
     * API endpoint for complete analysis overview
     */
    #[Route('/api/overview', name: 'overview', methods: ['GET'])]
    public function apiOverview(): JsonResponse
    {
        $analysis = $this->analysisService->getCompleteAnalysis();

        $simplified = [
            'summary' => [
                'total_playlist_types' => $analysis->summary->totalPlaylistTypes,
                'total_playlists' => $analysis->summary->totalPlaylists,
                'total_unique_songs' => $analysis->summary->totalUniqueSongs,
                'average_songs_per_type' => $analysis->summary->averageSongsPerType,
                'average_playlists_per_type' => $analysis->summary->averagePlaylistsPerType
            ],
            'types' => []
        ];

        foreach ($analysis->patternsByType as $type => $patterns) {
            $simplified['types'][$type] = [
                'total_playlists' => $patterns->totalPlaylists,
                'total_unique_songs' => $patterns->totalUniqueSongs,
                'common_songs_count' => count($patterns->commonSongs),
                'universal_songs_count' => count($patterns->universalSongs),
                'top_artist' => $patterns->topArtists[0]->artist->getName() ?? null
            ];
        }

        return $this->json($simplified);
    }
}
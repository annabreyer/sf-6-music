<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\PlaylistType;
use App\Service\PlaylistAnalysis\PlaylistAnalysisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/analysis', name: 'analysis_')]
class AnalysisController extends AbstractController
{
    public function __construct(
        private readonly PlaylistAnalysisService $analysisService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Main analysis dashboard
     */
    #[Route('/', name: 'dashboard')]
    public function dashboard(): Response
    {
        $completeAnalysis = $this->analysisService->getCompleteAnalysis();

        // Transform CompleteAnalysis DTO to array format expected by template
        $analysisData = [
            'summary' => $completeAnalysis->summary,
            'by_type' => [],
            'cross_type_comparisons' => []
        ];

        // Transform patterns by type
        foreach ($completeAnalysis->patternsByType as $type => $patterns) {
            $analysisData['by_type'][$type] = [
                'total_playlists' => $patterns->totalPlaylists,
                'total_unique_songs' => $patterns->totalUniqueSongs,
                'common_songs' => $patterns->commonSongs,
                'universal_songs' => $patterns->universalSongs,
                'top_artists' => $patterns->topArtists,
                'playlist_names' => $patterns->playlistNames
            ];
        }

        // Transform type comparisons
        foreach ($completeAnalysis->typeComparisons as $key => $comparison) {
            $analysisData['cross_type_comparisons'][$key] = $comparison;
        }

        return $this->render('analysis/dashboard.html.twig', [
            'analysis' => $analysisData
        ]);
    }

    /**
     * Analyze common songs for a specific playlist type
     */
    #[Route('/seasonal/{type}', name: 'seasonal')]
    public function seasonalAnalysis(string $type): Response
    {
        $patterns = $this->analysisService->getSeasonalPatterns($type);

        return $this->render('analysis/seasonal.html.twig', [
            'type' => $type,
            'patterns' => $patterns
        ]);
    }

    /**
     * Compare two playlist types
     */
    #[Route('/compare/{typeA}/{typeB}', name: 'compare')]
    public function compareTypes(string $typeA, string $typeB): Response
    {
        $comparison = $this->analysisService->comparePlaylistTypes($typeA, $typeB);

        return $this->render('analysis/compare.html.twig', [
            'comparison' => $comparison
        ]);
    }

    /**
     * Analyze similarity between two specific playlists
     */
    #[Route('/similarity/{playlistA}/{playlistB}', name: 'similarity')]
    public function playlistSimilarity(Playlist $playlistA, Playlist $playlistB): Response
    {
        $similarity = $this->analysisService->calculatePlaylistSimilarity($playlistA, $playlistB);

        return $this->render('analysis/similarity.html.twig', [
            'playlist_a' => $playlistA,
            'playlist_b' => $playlistB,
            'similarity' => $similarity
        ]);
    }

    /**
     * Find unique songs in a specific playlist
     */
    #[Route('/unique/{id}', name: 'unique')]
    public function uniqueSongs(Playlist $playlist): Response
    {
        $uniqueSongs = $this->analysisService->findUniqueSongs($playlist);

        return $this->render('analysis/unique.html.twig', [
            'playlist' => $playlist,
            'unique_songs' => $uniqueSongs
        ]);
    }

    /**
     * API endpoint for common songs in a playlist type
     */
    #[Route('/api/common/{type}', name: 'api_common', methods: ['GET'])]
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

    /**
     * API endpoint for universal songs (appearing in ALL playlists of a type)
     */
    #[Route('/api/universal/{type}', name: 'api_universal', methods: ['GET'])]
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
    #[Route('/api/compare/{typeA}/{typeB}', name: 'api_compare', methods: ['GET'])]
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
    #[Route('/api/overview', name: 'api_overview', methods: ['GET'])]
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

    /**
     * Interactive analysis page with forms for custom queries
     */
    #[Route('/interactive', name: 'interactive')]
    public function interactive(Request $request): Response
    {
        $results = null;
        $queryType = $request->query->get('query_type');

        switch ($queryType) {
            case 'common_songs':
                $type = $request->query->get('type');
                if ($type) {
                    $results = [
                        'type' => 'common_songs',
                        'playlist_type' => $type,
                        'data' => $this->analysisService->findCommonSeasonalSongs($type)
                    ];
                }
                break;

            case 'playlist_similarity':
                $playlistA = $request->query->get('playlist_a');
                $playlistB = $request->query->get('playlist_b');
                if ($playlistA && $playlistB) {
                    $pA = $this->entityManager->getRepository(Playlist::class)->find($playlistA);
                    $pB = $this->entityManager->getRepository(Playlist::class)->find($playlistB);

                    if ($pA && $pB) {
                        $results = [
                            'type' => 'playlist_similarity',
                            'playlist_a' => $pA,
                            'playlist_b' => $pB,
                            'similarity' => $this->analysisService->calculatePlaylistSimilarity($pA, $pB)
                        ];
                    }
                }
                break;

            case 'type_comparison':
                $typeA = $request->query->get('type_a');
                $typeB = $request->query->get('type_b');
                if ($typeA && $typeB) {
                    $results = [
                        'type' => 'type_comparison',
                        'data' => $this->analysisService->comparePlaylistTypes($typeA, $typeB)
                    ];
                }
                break;
        }

        // Get available data for form options
        $availableTypes = array_map(
            fn(PlaylistType $type) => $type->getType(),
            $this->entityManager->getRepository(PlaylistType::class)->findAll()
        );
        $availablePlaylists = $this->entityManager->getRepository(Playlist::class)->findAll();

        return $this->render('analysis/interactive.html.twig', [
            'results' => $results,
            'available_types' => $availableTypes,
            'available_playlists' => $availablePlaylists,
            'current_query' => $queryType
        ]);
    }
}

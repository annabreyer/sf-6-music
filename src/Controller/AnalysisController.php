<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\PlaylistType;
use App\Service\PlaylistAnalysis\PlaylistAnalysisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/', name: 'dashboard')]
    public function dashboard(): Response
    {
        $completeAnalysis = $this->analysisService->getCompleteAnalysis();

        return $this->render('analysis/dashboard.html.twig', [
            'analysis' => $completeAnalysis
        ]);
    }

    #[Route('/seasonal/{type}', name: 'seasonal')]
    public function seasonalAnalysis(string $type): Response
    {
        $patterns = $this->analysisService->getSeasonalPatterns($type);

        return $this->render('analysis/seasonal.html.twig', [
            'type' => $type,
            'patterns' => $patterns
        ]);
    }

    #[Route('/compare/{typeA}/{typeB}', name: 'compare')]
    public function compareTypes(string $typeA, string $typeB): Response
    {
        $comparison = $this->analysisService->comparePlaylistTypes($typeA, $typeB);

        return $this->render('analysis/compare.html.twig', [
            'comparison' => $comparison
        ]);
    }

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

    #[Route('/unique/{id}', name: 'unique')]
    public function uniqueSongs(Playlist $playlist): Response
    {
        $uniqueSongs = $this->analysisService->findUniqueSongs($playlist);

        return $this->render('analysis/unique.html.twig', [
            'playlist' => $playlist,
            'unique_songs' => $uniqueSongs
        ]);
    }

    #[Route('/interactive', name: 'interactive')]
    public function interactive(Request $request): Response
    {
        $results = null;
        $queryType = $request->query->get('query_type');

        switch ($queryType) {
            case 'common_songs':
                $type = $request->query->get('type');
                if ($type) {
                    $commonSongs = $this->analysisService->findCommonSeasonalSongs($type);
                    $results = [
                        'type' => 'common_songs',
                        'playlist_type' => $type,
                        'data' => $commonSongs
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
                        $similarity = $this->analysisService->calculatePlaylistSimilarity($pA, $pB);
                        $results = [
                            'type' => 'playlist_similarity',
                            'playlist_a' => $pA,
                            'playlist_b' => $pB,
                            'similarity' => $similarity
                        ];
                    }
                }
                break;

            case 'type_comparison':
                $typeA = $request->query->get('type_a');
                $typeB = $request->query->get('type_b');
                if ($typeA && $typeB) {
                    $comparison = $this->analysisService->comparePlaylistTypes($typeA, $typeB);
                    $results = [
                        'type' => 'type_comparison',
                        'data' => $comparison
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

<?php

declare(strict_types = 1);

namespace App\Tests\Service;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Playlist;
use App\Entity\PlaylistType;
use App\Entity\Song;
use App\Service\PlaylistAnalysis\PlaylistAnalysisService;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class PlaylistAnalysisServiceTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;
    protected PlaylistAnalysisService $analysisService;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->analysisService = self::getContainer()->get(PlaylistAnalysisService::class);

        // Set up test data
        $this->createTestData();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testFindCommonSeasonalSongs(): void
    {
        $commonSongs = $this->analysisService->findCommonSeasonalSongs('summer');

        $this->assertIsArray($commonSongs);
        $this->assertGreaterThan(0, count($commonSongs));

        // Verify the structure of returned data
        if (!empty($commonSongs)) {
            $firstSong = $commonSongs[0];
            $this->assertArrayHasKey('song', $firstSong);
            $this->assertArrayHasKey('count', $firstSong);
            $this->assertArrayHasKey('playlists', $firstSong);
            $this->assertInstanceOf(Song::class, $firstSong['song']);
            $this->assertGreaterThan(1, $firstSong['count']); // Should be in multiple playlists
        }
    }

    public function testCalculatePlaylistSimilarity(): void
    {
        $playlists = $this->entityManager->getRepository(Playlist::class)->findAll();

        if (count($playlists) >= 2) {
            $similarity = $this->analysisService->calculatePlaylistSimilarity($playlists[0], $playlists[1]);

            $this->assertIsFloat($similarity);
            $this->assertGreaterThanOrEqual(0, $similarity);
            $this->assertLessThanOrEqual(100, $similarity);
        } else {
            $this->markTestSkipped('Need at least 2 playlists for similarity test');
        }
    }

    public function testGetSeasonalPatterns(): void
    {
        $patterns = $this->analysisService->getSeasonalPatterns('summer');

        $this->assertIsArray($patterns);
        $this->assertArrayHasKey('type', $patterns);
        $this->assertArrayHasKey('total_playlists', $patterns);
        $this->assertArrayHasKey('total_unique_songs', $patterns);
        $this->assertArrayHasKey('common_songs', $patterns);
        $this->assertArrayHasKey('top_artists', $patterns);

        $this->assertEquals('summer', $patterns['type']);
    }

    public function testGetMostFrequentArtists(): void
    {
        $playlists = $this->entityManager->getRepository(Playlist::class)->findAll();
        $frequentArtists = $this->analysisService->getMostFrequentArtists($playlists, 5);

        $this->assertIsArray($frequentArtists);
        $this->assertLessThanOrEqual(5, count($frequentArtists));

        if (!empty($frequentArtists)) {
            $firstArtist = $frequentArtists[0];
            $this->assertArrayHasKey('artist', $firstArtist);
            $this->assertArrayHasKey('song_count', $firstArtist);
            $this->assertArrayHasKey('playlist_count', $firstArtist);
            $this->assertInstanceOf(Artist::class, $firstArtist['artist']);
        }
    }

    public function testComparePlaylistTypes(): void
    {
        $comparison = $this->analysisService->comparePlaylistTypes('summer', 'theme');

        $this->assertIsArray($comparison);
        $this->assertArrayHasKey('type_a', $comparison);
        $this->assertArrayHasKey('type_b', $comparison);
        $this->assertArrayHasKey('similarity_percentage', $comparison);
        $this->assertArrayHasKey('common_songs', $comparison);
        $this->assertArrayHasKey('unique_to_a', $comparison);
        $this->assertArrayHasKey('unique_to_b', $comparison);

        $this->assertEquals('summer', $comparison['type_a']);
        $this->assertEquals('theme', $comparison['type_b']);
    }

    public function testGetCompleteAnalysis(): void
    {
        $analysis = $this->analysisService->getCompleteAnalysis();

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('by_type', $analysis);
        $this->assertArrayHasKey('cross_type_comparisons', $analysis);
        $this->assertArrayHasKey('summary', $analysis);

        $summary = $analysis['summary'];
        $this->assertArrayHasKey('total_playlist_types', $summary);
        $this->assertArrayHasKey('total_playlists', $summary);
        $this->assertArrayHasKey('total_unique_songs', $summary);
    }

    private function createTestData(): void
    {
        // Create playlist types
        $summerType = new PlaylistType('summer');
        $themeType = new PlaylistType('theme');

        $this->entityManager->persist($summerType);
        $this->entityManager->persist($themeType);

        // Create artists
        $artist1 = new Artist('Test Artist 1');
        $artist2 = new Artist('Test Artist 2');

        $this->entityManager->persist($artist1);
        $this->entityManager->persist($artist2);

        // Create albums
        $album1 = new Album('Test Album 1');
        $album1->setArtist($artist1);
        $album2 = new Album('Test Album 2');
        $album2->setArtist($artist2);

        $this->entityManager->persist($album1);
        $this->entityManager->persist($album2);

        // Create songs
        $song1 = new Song();
        $song1->setTitle('Common Song 1')
              ->setArtist($artist1)
              ->setAlbum($album1);

        $song2 = new Song();
        $song2->setTitle('Common Song 2')
              ->setArtist($artist2)
              ->setAlbum($album2);

        $song3 = new Song();
        $song3->setTitle('Unique Song 1')
              ->setArtist($artist1)
              ->setAlbum($album1);

        $song4 = new Song();
        $song4->setTitle('Unique Song 2')
              ->setArtist($artist2)
              ->setAlbum($album2);

        $this->entityManager->persist($song1);
        $this->entityManager->persist($song2);
        $this->entityManager->persist($song3);
        $this->entityManager->persist($song4);

        // Create playlists
        $playlist1 = new Playlist();
        $playlist1->setName('Summer 2024')
                  ->addType($summerType)
                  ->addSong($song1)  // Common song
                  ->addSong($song2)  // Common song
                  ->addSong($song3); // Unique to this playlist

        $playlist2 = new Playlist();
        $playlist2->setName('Summer Vibes')
                  ->addType($summerType)
                  ->addSong($song1)  // Common song
                  ->addSong($song2)  // Common song
                  ->addSong($song4); // Unique to this playlist

        $playlist3 = new Playlist();
        $playlist3->setName('Coding Theme')
                  ->addType($themeType)
                  ->addSong($song3)  // Different songs for theme type
                  ->addSong($song4);

        $this->entityManager->persist($playlist1);
        $this->entityManager->persist($playlist2);
        $this->entityManager->persist($playlist3);

        $this->entityManager->flush();
    }
}

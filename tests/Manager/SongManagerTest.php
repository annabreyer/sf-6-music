<?php declare(strict_types = 1);

namespace App\Tests\Manager;

use App\DataFixtures\ArtistFixtures;
use App\DataFixtures\SongFixtures;
use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use App\Manager\AlbumManager;
use App\Manager\ArtistManager;
use App\Manager\SongManager;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SongManagerTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;
    protected SongManager $songManager;
    protected SongRepository $songRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool   = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager  = self::getContainer()->get(EntityManagerInterface::class);
        $this->songRepository = $this->entityManager->getRepository(Song::class);

        $managerRegistry  = self::getContainer()->get('doctrine');
        $artistRepository = $this->entityManager->getRepository(Artist::class);

        $albumManager = new AlbumManager(
            $this->entityManager->getRepository(Album::class),
            $artistRepository,
            new ArtistManager($artistRepository)
        );

        $this->songManager = new SongManager($this->songRepository, $albumManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCreateSongReturnsExistingSong()
    {
        $this->databaseTool->loadFixtures([SongFixtures::class]);
        $existingSong = $this->songRepository->findOneByTitleAndArtist('Ode to Unit Testing', 'Major Artist');
        $song         = $this->songManager->createSong('Ode to Unit Testing', 'Major Artist', 'Greatest Hits');

        $this->assertEquals($existingSong->getId(), $song->getId());
    }

    public function testCreateSongReturnsNewSong()
    {
        $this->databaseTool->loadFixtures([ArtistFixtures::class]);
        $song  = $this->songManager->createSong('Let\'s code', 'Hoodie', 'Coding serenates');
        $this->assertEquals('Let\'s code', $song->getTitle());
        $this->assertEquals('Hoodie', $song->getArtist()->getName());
        $this->assertEquals('Coding serenates', $song->getAlbum()->getName());
    }
}

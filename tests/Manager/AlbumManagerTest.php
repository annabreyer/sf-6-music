<?php declare(strict_types = 1);

namespace App\Tests\Manager;

use App\DataFixtures\AlbumFixtures;
use App\Entity\Album;
use App\Entity\Artist;
use App\Manager\AlbumManager;
use App\Manager\ArtistManager;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumManagerTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;
    protected AlbumManager $albumManager;
    protected AlbumRepository $albumRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool   = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager  = self::getContainer()->get(EntityManagerInterface::class);
        $this->albumRepository = $this->entityManager->getRepository(Album::class);

        $artistRepository = $this->entityManager->getRepository(Artist::class);
        $managerRegistry  = self::getContainer()->get('doctrine');

        $this->albumManager = new AlbumManager(
            $managerRegistry,
            $this->albumRepository,
            $artistRepository,
            new ArtistManager($managerRegistry, $artistRepository)
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCreateAlbumReturnsExistingAlbum(): void
    {
        $this->databaseTool->loadFixtures([AlbumFixtures::class]);
        $existingAlbum = $this->albumRepository->findOneByNameAndArtist('Greatest Hits', 'Major Artist');
        $album         = $this->albumManager->createAlbum('Greatest Hits', 'Major Artist');

        $this->assertEquals($existingAlbum->getId(), $album->getId());
    }

    public function testCreateAlbumReturnsNewAlbum(): void
    {
        $album = $this->albumManager->createAlbum('First Love', 'New Artist');
        $this->assertEquals('First Love', $album->getName());
        $this->assertEquals('New Artist', $album->getArtist()->getName());
    }
}

<?php

declare(strict_types = 1);

namespace App\Tests\Repository;

use App\DataFixtures\AlbumFixtures;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AlbumRepositoryTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;
    protected AlbumRepository $albumRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool    = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager   = self::getContainer()->get(EntityManagerInterface::class);
        $this->albumRepository = $this->entityManager->getRepository(Album::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testFindOneByNameAndArtist(): void
    {
        $this->databaseTool->loadFixtures([AlbumFixtures::class]);

        $album = $this->albumRepository->findOneByNameAndArtist('Greatest Hits', 'Major Artist');

        $this->assertNotNull($album);
        $this->assertEquals('Greatest Hits', $album->getName());
        $this->assertEquals('Major Artist', $album->getArtist()->getName());
    }
}

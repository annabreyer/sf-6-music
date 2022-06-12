<?php

declare(strict_types = 1);

namespace App\Tests\Repository;

use App\DataFixtures\SongFixtures;
use App\Entity\Song;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SongRepositoryTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;
    protected SongRepository $songRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool   = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager  = self::getContainer()->get(EntityManagerInterface::class);
        $this->songRepository = $this->entityManager->getRepository(Song::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testFindOneByTitleAndArtist(): void
    {
        $this->databaseTool->loadFixtures([SongFixtures::class]);

        $song = $this->songRepository->findOneByTitleAndArtist('Ode to Unit Testing', 'Major Artist');

        $this->assertNotNull($song);
        $this->assertEquals('Ode to Unit Testing', $song->getTitle());
        $this->assertEquals('Major Artist', $song->getArtist()->getName());
    }
}

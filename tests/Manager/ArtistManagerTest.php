<?php

declare(strict_types = 1);

namespace App\Tests\Manager;

use App\DataFixtures\ArtistFixtures;
use App\Entity\Artist;
use App\Manager\ArtistManager;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArtistManagerTest extends KernelTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected EntityManagerInterface $entityManager;
    protected ArtistManager $artistManager;
    protected ArtistRepository $artistRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool     = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager    = self::getContainer()->get(EntityManagerInterface::class);
        $this->artistRepository = $this->entityManager->getRepository(Artist::class);

        $this->artistManager = new ArtistManager($this->artistRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCreateArtistReturnsExistingArtist(): void
    {
        $this->databaseTool->loadFixtures([ArtistFixtures::class]);
        $existingArtist = $this->artistRepository->findOneBy(['name' => 'Major Artist']);
        $artist         = $this->artistManager->createArtist('Major Artist');

        $this->assertEquals($existingArtist->getId(), $artist->getId());
    }

    public function testCreateArtistReturnsNewArtist(): void
    {
        $artist = $this->artistManager->createArtist('Up and coming Artist');
        $this->assertEquals('Up and coming Artist', $artist->getName());
    }
}

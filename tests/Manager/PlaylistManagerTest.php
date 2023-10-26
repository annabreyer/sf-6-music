<?php

declare(strict_types = 1);

namespace App\Tests\Manager;

use App\DataFixtures\PlaylistFixtures;
use App\DataFixtures\PlaylistTypeFixtures;
use App\Entity\Playlist;
use App\Entity\PlaylistType;
use App\Exceptions\InvalidTypeException;
use App\Manager\PlaylistManager;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistManagerTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;
    protected PlaylistManager $playlistManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool       = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager      = self::getContainer()->get(EntityManagerInterface::class);
        $this->playlistRepository = $this->entityManager->getRepository(Playlist::class);
        $playlistTypeRepository   = $this->entityManager->getRepository(PlaylistType::class);
        $managerRegistry          = self::getContainer()->get('doctrine');

        $this->playlistManager = new PlaylistManager($this->playlistRepository, $playlistTypeRepository, $managerRegistry);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCreatePlaylistThrowsErrorByUnknownType(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('PlaylistType BLABLA does not exist.');
        $this->playlistManager->createPlaylist('Hot Testtracks', 'BLABLA');
    }

    public function testCreatePlaylistReturnsExistingPlaylist(): void
    {
        $this->databaseTool->loadFixtures([PlaylistTypeFixtures::class, PlaylistFixtures::class]);
        $existingPlaylist = $this->playlistRepository->findOneBy(['name' => 'Code away']);
        $playlist         = $this->playlistManager->createPlaylist('Code away', PlaylistType::TYPE_THEME);

        $this->assertEquals($existingPlaylist->getId(), $playlist->getId());
    }

    public function testCreatePlaylistReturnsNewPlaylist(): void
    {
        $this->databaseTool->loadFixtures([PlaylistTypeFixtures::class, PlaylistFixtures::class]);
        $playlist = $this->playlistManager->createPlaylist('Hot Testtracks', PlaylistType::TYPE_THEME);
        $this->assertEquals('Hot Testtracks', $playlist->getName());
        $this->assertEquals(PlaylistType::TYPE_THEME, $playlist->getTypes()->first()->getType());
    }
}

<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class AlbumManager
{
    private ObjectManager $entityManager;
    private AlbumRepository $albumRepository;
    private ArtistRepository $artistRepository;
    private ArtistManager $artistManager;

    public function __construct(
        ManagerRegistry $managerRegistry,
        AlbumRepository $albumRepository,
        ArtistRepository $artistRepository,
        ArtistManager $artistManager
    ) {
        $this->entityManager    = $managerRegistry->getManager();
        $this->albumRepository  = $albumRepository;
        $this->artistRepository = $artistRepository;
        $this->artistManager    = $artistManager;
    }

    public function createAlbum(string $albumName, string $artistName): Album
    {
        $album = $this->albumRepository->findOneByNameAndArtist($albumName, $artistName);

        if (null !== $album){
            return $album;
        }

        $artist = $this->artistRepository->findOneBy(['name' => $artistName]);
        if (null === $artist){
            $artist = $this->artistManager->createArtist($artistName);
        }

        $album = new Album($albumName);
        $album->setArtist($artist);

        $this->entityManager->persist($album);
        $this->entityManager->flush();
        $this->entityManager->refresh($album);

        return $album;
    }
}

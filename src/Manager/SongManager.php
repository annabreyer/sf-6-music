<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Song;
use App\Repository\SongRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class SongManager
{
    private ObjectManager $entityManager;

    public function __construct(
        private SongRepository $songRepository,
        private AlbumManager $albumManager,
        ManagerRegistry $managerRegistry
    ) {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function createSong(string $title, string $artist, string $albumName): Song
    {
        $song = $this->songRepository->findOneByTitleAndArtist($title, $artist);

        if (null !== $song && $albumName === $song->getAlbum()->getName()){
            return $song;
        }

        $album = $this->albumManager->createAlbum($albumName, $artist);
        $song  = new Song();
        $song->setTitle($title)
             ->setArtist($album->getArtist())
             ->setAlbum($album)
        ;

        $this->entityManager->persist($song);
        $this->entityManager->flush();
        $this->entityManager->refresh($song);

        return $song;
    }
}

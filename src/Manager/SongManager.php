<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Song;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;

class SongManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SongRepository $songRepository,
        private AlbumManager $albumManager,
    ) {
    }

    public function createSong(string $title, string $artist, string $albumName): Song
    {
        $song = $this->songRepository->findOneByTitleAndArtist($title, $artist);

        if (null !== $song && $albumName === $song->getAlbum()->getName()) {
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

        return $song;
    }
}

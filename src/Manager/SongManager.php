<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Song;
use App\Repository\SongRepository;

class SongManager
{
    public function __construct(
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

        $this->songRepository->add($song);

        return $song;
    }
}

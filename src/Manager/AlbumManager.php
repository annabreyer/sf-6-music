<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;

class AlbumManager
{
    public function __construct(
        private AlbumRepository $albumRepository,
        private ArtistRepository $artistRepository,
        private ArtistManager $artistManager,
    ) {}

    public function createAlbum(string $albumName, string $artistName): Album
    {
        $album = $this->albumRepository->findOneByNameAndArtist($albumName, $artistName);

        if (null !== $album) {
            return $album;
        }

        $artist = $this->artistRepository->findOneBy(['name' => $artistName]);
        if (null === $artist) {
            $artist = $this->artistManager->createArtist($artistName);
        }

        $album = new Album($albumName);
        $album->setArtist($artist);

        $this->albumRepository->add($album);

        return $album;
    }
}

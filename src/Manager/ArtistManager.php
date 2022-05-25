<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Artist;
use App\Repository\ArtistRepository;

class ArtistManager
{
    public function __construct(private ArtistRepository $artistRepository)
    {
    }

    public function createArtist(string $name)
    {
        $artist = $this->artistRepository->findOneBy(['name' => $name]);

        if (null !== $artist) {
            return $artist;
        }

        $artist = new Artist($name);
        $this->artistRepository->add($artist);

        return $artist;
    }
}

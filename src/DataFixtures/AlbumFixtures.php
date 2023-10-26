<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AlbumFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Major Artist');
        $album  = new Album('Greatest Hits');
        $album->setArtist($artist);

        $manager->persist($album);
        $manager->flush();

        $this->addReference('greatestHitsAlbum', $album);
    }
}

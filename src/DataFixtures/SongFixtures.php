<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SongFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Major Artist');
        $album  = new Album('Greatest Hits');
        $album->setArtist($artist);

        $song   = new Song();
        $song->setTitle('Ode to Unit Testing')
            ->setArtist($artist)
            ->setAlbum($album);

        $manager->persist($song);
        $manager->flush();


        $artist = new Artist('Up and Coming Artist');
        $album  = new Album('Debut');
        $album->setArtist($artist);

        $song   = new Song();
        $song->setTitle('Love')
             ->setArtist($artist)
             ->setAlbum($album);

        $manager->persist($song);
        $manager->flush();
    }
}

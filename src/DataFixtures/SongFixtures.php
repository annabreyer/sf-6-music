<?php

declare(strict_types = 1);

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
        // First artist and album
        $artist = new Artist('Major Artist');
        $album  = new Album('Greatest Hits');
        $album->setArtist($artist);

        $song = new Song();
        $song->setTitle('Ode to Unit Testing')
             ->setArtist($artist)
             ->setAlbum($album)
        ;

        $manager->persist($artist);
        $manager->persist($album);
        $manager->persist($song);

        // Second artist and album
        $artist2 = new Artist('Up and Coming Artist');
        $album2  = new Album('Debut');
        $album2->setArtist($artist2);

        $song2 = new Song();
        $song2->setTitle('Love')
              ->setArtist($artist2)
              ->setAlbum($album2)
        ;

        $manager->persist($artist2);
        $manager->persist($album2);
        $manager->persist($song2);

        $manager->flush();

        // Add references for use in other fixtures
        $this->addReference('majorArtist', $artist);
        $this->addReference('greatestHitsAlbum', $album);
        $this->addReference('odeToUnitTestingSong', $song);
        $this->addReference('upAndComingArtist', $artist2);
        $this->addReference('debutAlbum', $album2);
        $this->addReference('loveSong', $song2);
    }
}

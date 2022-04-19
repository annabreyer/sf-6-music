<?php declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Major Artist');

        $manager->persist($artist);
        $manager->flush();
    }
}
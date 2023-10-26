<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Playlist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaylistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $playlist     = new Playlist();
        $playlist->setName('Code away')
            ->addType($this->getReference('typeTheme'))
        ;
        $manager->persist($playlist);
        $manager->flush();
    }
}

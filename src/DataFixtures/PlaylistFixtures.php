<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Playlist;
use App\Entity\PlaylistType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlaylistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var PlaylistType $typeTheme */
        $typeTheme = $this->getReference('typeTheme', PlaylistType::class);

        $playlist = new Playlist();
        $playlist->setName('Code away')
                 ->addType($typeTheme)
        ;
        $manager->persist($playlist);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PlaylistTypeFixtures::class,
        ];
    }
}

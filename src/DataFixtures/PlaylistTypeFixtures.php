<?php declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\PlaylistType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaylistTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeTheme = new PlaylistType(PlaylistType::TYPE_THEME);
        $manager->persist($typeTheme);
        $manager->flush();

        $this->addReference('typeTheme', $typeTheme);
    }
}

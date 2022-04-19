<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class ArtistManager
{
    private ObjectManager $entityManager;
    private ArtistRepository $artistRepository;

    public function __construct(ManagerRegistry $managerRegistry, ArtistRepository $artistRepository)
    {
        $this->artistRepository = $artistRepository;
        $this->entityManager = $managerRegistry->getManager();
    }

    public function createArtist(string $name)
    {
        $artist = $this->artistRepository->findOneBy(['name' => $name]);

        if (null !== $artist){
            return $artist;
        }

        $artist = new Artist($name);
        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        $this->entityManager->refresh($artist);

        return $artist;
    }
}

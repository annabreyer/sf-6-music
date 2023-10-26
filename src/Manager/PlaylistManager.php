<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Playlist;
use App\Entity\PlaylistType;
use App\Exceptions\InvalidTypeException;
use App\Repository\PlaylistRepository;
use App\Repository\PlaylistTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class PlaylistManager
{
    private ObjectManager $entityManager;

    public function __construct(
        private PlaylistRepository $playlistRepository,
        private PlaylistTypeRepository $playlistTypeRepository,
        ManagerRegistry $managerRegistry
    ) {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function createPlaylist(string $name, string $type): Playlist
    {
        $playlistType = $this->playlistTypeRepository->findOneBy(['type' => $type]);

        if (null === $playlistType) {
            throw new InvalidTypeException('PlaylistType '.$type.' does not exist.');
        }

        $playlist = $this->playlistRepository->findOneBy(['name' => $name]);

        if (null !== $playlist && $playlist->getTypes()->contains($playlistType)) {
            return $playlist;
        }

        if (null !== $playlist && false === $playlist->getTypes()->contains($playlistType)) {
            return $this->addTypeToPlaylist($playlist, $playlistType);
        }

        $playlist = new Playlist();
        $playlist->setName($name)
            ->addType($playlistType)
        ;

        $this->playlistRepository->add($playlist);

        return $playlist;
    }

    public function addTypeToPlaylist(Playlist $playlist, PlaylistType $type): Playlist
    {
        $playlist->addType($type);
        $this->entityManager->flush();

        return $playlist;
    }
}

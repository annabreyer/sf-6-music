<?php declare(strict_types=1);

namespace App\Service\PlaylistAnalysis;

use App\Repository\PlaylistRepository;
use App\Repository\PlaylistTypeRepository;
use App\Service\PlaylistAnalysis\Contract\PlaylistDataProviderInterface;

class DoctrinePlaylistDataProvider implements PlaylistDataProviderInterface
{
    public function __construct(
        private readonly PlaylistRepository $playlistRepository,
        private readonly PlaylistTypeRepository $playlistTypeRepository
    ) {
    }

    public function getPlaylistsByType(string $type): array
    {
        return $this->playlistRepository->createQueryBuilder('p')
                                        ->innerJoin('p.types', 't')
                                        ->where('t.type = :type')
                                        ->setParameter('type', $type)
                                        ->getQuery()
                                        ->getResult();
    }

    public function getAllPlaylistTypes(): array
    {
        $types = $this->playlistTypeRepository->findAll();
        return array_map(fn($type) => $type->getType(), $types);
    }
}

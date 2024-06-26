<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Artist;
use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Song find($id, $lockMode = null, $lockVersion = null)
 * @method null|Song findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Song>
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function findOneByTitleAndArtist(string $title, string $artist): ?Song
    {
        return $this->createQueryBuilder('s')
            ->innerJoin(Artist::class, 'a', Join::WITH, 'a.id = s.artist')
            ->andWhere('s.title = :title')
            ->andWhere('a.name = :artist')
            ->setParameter('title', $title)
            ->setParameter('artist', $artist)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

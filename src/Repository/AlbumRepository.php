<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Album;
use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Album find($id, $lockMode = null, $lockVersion = null)
 * @method null|Album findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Album $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Album $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOneByNameAndArtist(string $albumName, string $artist): ?Album
    {
        return $this->createQueryBuilder('alb')
            ->innerJoin(Artist::class, 'art', Join::WITH, 'art.id = alb.artist')
            ->andWhere('alb.name = :name')
            ->andWhere('art.name = :artist')
            ->setParameter('name', $albumName)
            ->setParameter('artist', $artist)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

<?php

namespace App\Repository;

use App\Entity\Wine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Wine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wine[]    findAll()
 * @method Wine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wine::class);
    }

    public function getDistinctYears()
    {
        return $this->createQueryBuilder('w')
            ->select('w.year')
            ->orderBy('w.year')
            ->distinct()
            ->getQuery()
            ->getScalarResult();
    }

    public function getDistinctDluoYears()
    {
        return $this->createQueryBuilder('w')
            ->select('w.dluo')
            ->orderBy('w.dluo')
            ->distinct()
            ->getQuery()
            ->getScalarResult();
    }

    public function findNotEmpty()
    {
        return $this->createQueryBuilder('w')
        ->where('w.nbBottles > 0')
        ->getQuery()
        ->execute();
    }

    public function findEmpty()
    {
        return $this->createQueryBuilder('w')
        ->where('w.nbBottles = 0')
        ->getQuery()
        ->execute();
    }

    public function countBottles()
    {
        return $this->createQueryBuilder('w')
        ->select('sum(w.nbBottles)')
        ->getQuery()
        ->getSingleScalarResult();
    }
}

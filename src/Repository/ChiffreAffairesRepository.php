<?php

namespace App\Repository;

use App\Entity\ChiffreAffaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChiffreAffairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChiffreAffaires::class);
    }

    /**
     * Find users without chiffre d'affaires for the given month.
     *
     * @param \DateTimeInterface $month
     * @return array
     */
    public function findUsersWithoutCAForMonth(\DateTimeInterface $month): array
    {
        return $this->createQueryBuilder('ca')
            ->select('u')
            ->from('App\Entity\User', 'u')
            ->leftJoin('ca.user', 'uca')
            ->where('ca.mois = :month')
            ->andWhere('uca.id IS NULL')
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult();
    }
}

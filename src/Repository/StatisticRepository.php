<?php

namespace App\Repository;

use App\Entity\Statistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    public function updateStatistics(string $region, string $departement): void
    {
        $entityManager = $this->getEntityManager();

        // Calcul du CA moyen par région
        $avgCaRegion = $entityManager->createQuery(
            'SELECT AVG(c.chiffre_affaires) 
             FROM App\Entity\ChiffreAffaires c 
             JOIN c.user u 
             JOIN u.salon s 
             WHERE s.region = :region'
        )
        ->setParameter('region', $region)
        ->getSingleScalarResult();

        // Calcul du CA moyen par département
        $avgCaDepartement = $entityManager->createQuery(
            'SELECT AVG(c.chiffre_affaires) 
             FROM App\Entity\ChiffreAffaires c 
             JOIN c.user u 
             JOIN u.salon s 
             WHERE s.departement = :departement'
        )
        ->setParameter('departement', $departement)
        ->getSingleScalarResult();

        // Mise à jour ou création de l'enregistrement Statistic
        $statistic = $this->findOneBy(['region' => $region, 'departement' => $departement]);
        if (!$statistic) {
            $statistic = new Statistic();
            $statistic->setRegion($region);
            $statistic->setDepartement($departement);
            $entityManager->persist($statistic);
        }

        $statistic->setAverageCaRegion($avgCaRegion);
        $statistic->setAverageCaDepartement($avgCaDepartement);

        $entityManager->flush();
    }
}

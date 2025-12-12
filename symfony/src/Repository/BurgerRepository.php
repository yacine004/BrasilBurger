<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Burger>
 */
class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    public function findActif(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.statut = :statut')
            ->setParameter('statut', 'ACTIF')
            ->orderBy('b.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByNom(string $nom): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->orderBy('b.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findRecent(int $limit = 5): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

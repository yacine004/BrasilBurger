<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zone>
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    /**
     * Trouver une zone par nom
     */
    public function findByNom(string $nom): ?Zone
    {
        return $this->findOneBy(['nom' => $nom]);
    }

    /**
     * Trouver toutes les zones triées par nom
     */
    public function findAllOrderedByName(): array
    {
        return $this->findBy([], ['nom' => 'ASC']);
    }
}

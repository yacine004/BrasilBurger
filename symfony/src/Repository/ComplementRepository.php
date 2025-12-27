<?php

namespace App\Repository;

use App\Entity\Complement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Complement>
 */
class ComplementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complement::class);
    }

    /**
     * Trouver les compléments actifs (non archivés)
     */
    public function findComplementsActifs(): array
    {
        return $this->findBy(['archive' => false], ['nom' => 'ASC']);
    }

    /**
     * Trouver un complément par nom
     */
    public function findByNom(string $nom): ?Complement
    {
        return $this->findOneBy(['nom' => $nom, 'archive' => false]);
    }
}

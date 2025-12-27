<?php

namespace App\Repository;

use App\Entity\Livreur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livreur>
 */
class LivreurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livreur::class);
    }

    /**
     * Trouver les livreurs actifs
     */
    public function findLivreursActifs(): array
    {
        return $this->findBy(['actif' => true], ['nom' => 'ASC', 'prenom' => 'ASC']);
    }

    /**
     * Trouver un livreur par téléphone
     */
    public function findByTelephone(string $telephone): ?Livreur
    {
        return $this->findOneBy(['telephone' => $telephone]);
    }
}

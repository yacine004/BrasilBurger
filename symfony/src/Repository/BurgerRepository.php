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

    /**
     * Trouver les burgers actifs (non archivés)
     */
    public function findBurgersActifs(): array
    {
        return $this->findBy(['archive' => false], ['nom' => 'ASC']);
    }

    /**
     * Trouver un burger par nom
     */
    public function findByNom(string $nom): ?Burger
    {
        return $this->findOneBy(['nom' => $nom, 'archive' => false]);
    }

    /**
     * Burgers les plus vendus du jour
     */
    public function findBurgersPlusVendusJour(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b, COUNT(cb.id) as ventes')
            ->leftJoin('App\Entity\CommandeBurger', 'cb', 'WITH', 'cb.burger = b')
            ->leftJoin('App\Entity\Commande', 'c', 'WITH', 'cb.commande = c')
            ->andWhere('DATE(c.dateCommande) = CURRENT_DATE()')
            ->andWhere('b.archive = false')
            ->groupBy('b.id')
            ->orderBy('ventes', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}

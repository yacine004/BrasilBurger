<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    public function findActifs()
    {
        return $this->findBy(['etat' => true]);
    }

    public function findMostSoldToday()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $tomorrow = (clone $today)->modify('+1 day');
        
        // Utiliser une requête native SQL pour éviter les problèmes de performance
        $connection = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT DISTINCT b.id FROM burger b
            INNER JOIN commande_burger cb ON b.id = cb.id_burger
            INNER JOIN commande c ON cb.id_commande = c.id
            WHERE c.date >= :start AND c.date < :end
            ORDER BY b.id DESC
            LIMIT 10
        ";
        
        $stmt = $connection->prepare($sql);
        $result = $stmt->executeQuery([
            'start' => $today->format('Y-m-d H:i:s'),
            'end' => $tomorrow->format('Y-m-d H:i:s'),
        ]);
        
        $burgers = [];
        foreach ($result->fetchAllAssociative() as $row) {
            $burger = $this->find($row['id']);
            if ($burger) {
                $burgers[] = $burger;
            }
        }
        
        return $burgers;
    }
}

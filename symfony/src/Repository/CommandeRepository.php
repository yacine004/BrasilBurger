<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findByStatus(string $etat)
    {
        return $this->findBy(['etat' => $etat], ['date' => 'DESC']);
    }

    public function findTodayByStatus(string $etat)
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $tomorrow = (clone $today)->modify('+1 day');
        
        return $this->createQueryBuilder('c')
            ->where('c.date >= :start')
            ->andWhere('c.date < :end')
            ->andWhere('c.etat = :etat')
            ->setParameter('start', $today)
            ->setParameter('end', $tomorrow)
            ->setParameter('etat', $etat)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllToday()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $tomorrow = (clone $today)->modify('+1 day');
        
        return $this->createQueryBuilder('c')
            ->where('c.date >= :start')
            ->andWhere('c.date < :end')
            ->setParameter('start', $today)
            ->setParameter('end', $tomorrow)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByClient(int $clientId)
    {
        return $this->findBy(['client' => $clientId], ['date' => 'DESC']);
    }

    public function findByDateRange(\DateTime $startDate, \DateTime $endDate)
    {
        return $this->createQueryBuilder('c')
            ->where('c.date >= :start')
            ->andWhere('c.date <= :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

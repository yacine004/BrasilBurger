<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Trouver les commandes en cours du jour
     */
    public function findCommandesEnCoursJour(): array
    {
        $today = new DateTime('today');
        
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat IN (:statuts)')
            ->setParameter('statuts', [
                Commande::STATUS_CONFIRMEE,
                Commande::STATUS_EN_COURS
            ])
            ->andWhere('DATE(c.dateCommande) = DATE(:today)')
            ->setParameter('today', $today)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Trouver les commandes validées du jour
     */
    public function findCommandesValideeJour(): array
    {
        $today = new DateTime('today');
        
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat = :etat')
            ->setParameter('etat', Commande::STATUS_TERMINER)
            ->andWhere('c.payee = true')
            ->andWhere('DATE(c.dateCommande) = DATE(:today)')
            ->setParameter('today', $today)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Trouver les commandes annulées du jour
     */
    public function findCommandesAnnuleesJour(): array
    {
        $today = new DateTime('today');
        
        return $this->createQueryBuilder('c')
            ->andWhere('c.etat = :etat')
            ->setParameter('etat', Commande::STATUS_ANNULEE)
            ->andWhere('DATE(c.dateCommande) = DATE(:today)')
            ->setParameter('today', $today)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Trouver les commandes pour un client
     */
    public function findByClient($clientId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Trouver les commandes par état
     */
    public function findByEtat(string $etat): array
    {
        return $this->findBy(['etat' => $etat], ['dateCommande' => 'DESC']);
    }

    /**
     * Trouver les commandes entre deux dates
     */
    public function findCommandesBetweenDates(DateTime $dateDebut, DateTime $dateFin): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateCommande >= :dateDebut')
            ->andWhere('c.dateCommande <= :dateFin')
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}

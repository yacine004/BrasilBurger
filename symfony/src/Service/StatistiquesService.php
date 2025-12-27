<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Paiement;
use App\Repository\CommandeRepository;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class StatistiquesService
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private BurgerRepository $burgerRepository,
        private MenuRepository $menuRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Récupérer les commandes en cours du jour
     */
    public function getCommandesEnCoursJour(): array
    {
        return $this->commandeRepository->findCommandesEnCoursJour();
    }

    /**
     * Récupérer les commandes validées du jour
     */
    public function getCommandesValideeJour(): array
    {
        return $this->commandeRepository->findCommandesValideeJour();
    }

    /**
     * Récupérer les commandes annulées du jour
     */
    public function getCommandesAnnuleesJour(): array
    {
        return $this->commandeRepository->findCommandesAnnuleesJour();
    }

    /**
     * Calculer les recettes journalières
     */
    public function getRecettesJournalieres(): float
    {
        $commandes = $this->commandeRepository->findCommandesValideeJour();
        $total = 0;

        foreach ($commandes as $commande) {
            if ($commande->getPaiement()) {
                $total += $commande->getPaiement()->getMontant();
            }
        }

        return $total;
    }

    /**
     * Récupérer les burgers les plus vendus du jour
     */
    public function getBurgersPlusVendusJour(): array
    {
        return $this->burgerRepository->findBurgersPlusVendusJour();
    }

    /**
     * Compter les commandes en attente de paiement
     */
    public function countCommandesEnAttentePaiement(): int
    {
        $qb = $this->em->createQueryBuilder();
        return (int) $qb
            ->select('COUNT(c.id)')
            ->from(Commande::class, 'c')
            ->where('c.payee = false')
            ->andWhere('c.etat != :etat')
            ->setParameter('etat', Commande::STATUS_ANNULEE)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Récupérer les statistiques du jour
     */
    public function getStatistiquesJour(): array
    {
        return [
            'commandes_en_cours' => count($this->getCommandesEnCoursJour()),
            'commandes_validees' => count($this->getCommandesValideeJour()),
            'commandes_annulees' => count($this->getCommandesAnnuleesJour()),
            'recettes_journalieres' => $this->getRecettesJournalieres(),
            'burgers_plus_vendus' => $this->getBurgersPlusVendusJour(),
            'commandes_en_attente_paiement' => $this->countCommandesEnAttentePaiement(),
        ];
    }

    /**
     * Récupérer les statistiques par période
     */
    public function getStatistiquesPeriode(DateTime $dateDebut, DateTime $dateFin): array
    {
        $commandes = $this->commandeRepository->findCommandesBetweenDates($dateDebut, $dateFin);
        
        $stats = [
            'total_commandes' => count($commandes),
            'total_recettes' => 0,
            'commandes_payees' => 0,
            'commandes_en_attente' => 0,
            'commandes_annulees' => 0,
        ];

        foreach ($commandes as $commande) {
            if ($commande->isPayee()) {
                $stats['commandes_payees']++;
                if ($commande->getPaiement()) {
                    $stats['total_recettes'] += $commande->getPaiement()->getMontant();
                }
            } else if ($commande->getEtat() === Commande::STATUS_ANNULEE) {
                $stats['commandes_annulees']++;
            } else {
                $stats['commandes_en_attente']++;
            }
        }

        return $stats;
    }
}

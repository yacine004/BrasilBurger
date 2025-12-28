<?php

namespace App\Service;

use App\Entity\Commande;
use App\Repository\BurgerRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private BurgerRepository $burgerRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Récupère les commandes en cours de la journée
     */
    public function getCommandesEnCours(): array
    {
        return $this->commandeRepository->findTodayByStatus('En cours');
    }

    /**
     * Récupère les commandes validées de la journée
     */
    public function getCommandesValidees(): array
    {
        return $this->commandeRepository->findTodayByStatus('Validée');
    }

    /**
     * Récupère les commandes terminées de la journée
     */
    public function getCommandesTerminees(): array
    {
        return $this->commandeRepository->findTodayByStatus('Terminer');
    }

    /**
     * Récupère les commandes annulées du jour
     */
    public function getCommandesAnnulees(): array
    {
        return $this->commandeRepository->findTodayByStatus('Annulée');
    }

    /**
     * Calcule les recettes journalières
     */
    public function getRecettesJournalieres(): float
    {
        $commandes = $this->commandeRepository->findAllToday();
        $total = 0;

        foreach ($commandes as $commande) {
            // Compter seulement les commandes payées
            if ($commande->getPaiement() !== null) {
                $total += $commande->getMontant() ?? 0;
            }
        }

        return $total;
    }

    /**
     * Récupère les burgers les plus vendus du jour
     */
    public function getBurgersLesPlusVendus(): array
    {
        return $this->burgerRepository->findMostSoldToday();
    }

    /**
     * Récupère les statistiques complètes du jour
     */
    public function getStatistiquesJour(): array
    {
        $commandesEnCours = $this->getCommandesEnCours();
        $commandesValidees = $this->getCommandesValidees();
        $commandesTerminees = $this->getCommandesTerminees();
        $commandesAnnulees = $this->getCommandesAnnulees();
        $recettes = $this->getRecettesJournalieres();
        $burgersPlusVendus = $this->getBurgersLesPlusVendus();

        return [
            'commandesEnCours' => count($commandesEnCours),
            'commandesValidees' => count($commandesValidees),
            'commandesTerminees' => count($commandesTerminees),
            'commandesAnnulees' => count($commandesAnnulees),
            'recettes' => $recettes,
            'burgersPlusVendus' => $burgersPlusVendus,
        ];
    }
}

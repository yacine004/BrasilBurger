<?php

namespace App\Service;

class StatistiqueService
{
    public function __construct(private ApiService $apiService)
    {
    }

    public function getStatistiquesJour(): array
    {
        $commandes = $this->apiService->getCommandes();

        if (isset($commandes['error'])) {
            return $this->getEmptyStats();
        }

        $today = (new \DateTime())->format('Y-m-d');
        $commandesAujourdhui = [];

        foreach ($commandes as $commande) {
            if (isset($commande['dateCreation'])) {
                $dateCommande = substr($commande['dateCreation'], 0, 10);
                if ($dateCommande === $today) {
                    $commandesAujourdhui[] = $commande;
                }
            }
        }

        $stats = [
            'commandesEnCours' => 0,
            'commandesValidees' => 0,
            'commandesAnnulees' => 0,
            'recettesJournalieres' => 0,
            'totalCommandes' => count($commandesAujourdhui),
        ];

        foreach ($commandesAujourdhui as $commande) {
            $statut = $commande['statut'] ?? 'EN_ATTENTE';

            if ($statut === 'EN_COURS') {
                $stats['commandesEnCours']++;
            } elseif ($statut === 'VALIDEE') {
                $stats['commandesValidees']++;
            } elseif ($statut === 'ANNULEE') {
                $stats['commandesAnnulees']++;
            }

            if (isset($commande['montantTotal']) && $statut !== 'ANNULEE') {
                $stats['recettesJournalieres'] += (float)$commande['montantTotal'];
            }
        }

        return $stats;
    }

    public function getStatistiquesGlobales(): array
    {
        $commandes = $this->apiService->getCommandes();

        if (isset($commandes['error'])) {
            return $this->getEmptyStats();
        }

        $stats = [
            'totalCommandes' => count($commandes),
            'commandesEnCours' => 0,
            'commandesValidees' => 0,
            'commandesAnnulees' => 0,
            'recettesGlobales' => 0,
        ];

        foreach ($commandes as $commande) {
            $statut = $commande['statut'] ?? 'EN_ATTENTE';

            if ($statut === 'EN_COURS') {
                $stats['commandesEnCours']++;
            } elseif ($statut === 'VALIDEE') {
                $stats['commandesValidees']++;
            } elseif ($statut === 'ANNULEE') {
                $stats['commandesAnnulees']++;
            }

            if (isset($commande['montantTotal']) && $statut !== 'ANNULEE') {
                $stats['recettesGlobales'] += (float)$commande['montantTotal'];
            }
        }

        return $stats;
    }

    private function getEmptyStats(): array
    {
        return [
            'commandesEnCours' => 0,
            'commandesValidees' => 0,
            'commandesAnnulees' => 0,
            'recettesJournalieres' => 0,
            'recettesGlobales' => 0,
            'totalCommandes' => 0,
        ];
    }
}

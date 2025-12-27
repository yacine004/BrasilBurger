<?php

namespace App\Controller;

use App\Service\StatistiquesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use DateTime;

#[Route('/api/statistiques', name: 'api_statistiques_')]
class StatistiquesController extends AbstractController
{
    public function __construct(
        private StatistiquesService $statistiquesService
    ) {}

    /**
     * Récupérer les statistiques du jour
     */
    #[Route('/jour', name: 'jour', methods: ['GET'])]
    public function jour(): JsonResponse
    {
        $stats = $this->statistiquesService->getStatistiquesJour();

        return $this->json([
            'success' => true,
            'date' => (new DateTime())->format('Y-m-d'),
            'data' => [
                'commandes_en_cours' => $stats['commandes_en_cours'],
                'commandes_validees' => $stats['commandes_validees'],
                'commandes_annulees' => $stats['commandes_annulees'],
                'recettes_journalieres' => (float) $stats['recettes_journalieres'],
                'burgers_plus_vendus' => $stats['burgers_plus_vendus'],
                'commandes_en_attente_paiement' => $stats['commandes_en_attente_paiement']
            ]
        ]);
    }

    /**
     * Récupérer les statistiques par période
     */
    #[Route('/periode', name: 'periode', methods: ['GET'])]
    public function periode(Request $request): JsonResponse
    {
        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('dateFin');

        if (!$dateDebut || !$dateFin) {
            return $this->json([
                'error' => 'Les paramètres dateDebut et dateFin sont obligatoires'
            ], 400);
        }

        try {
            $dateDebut = new DateTime($dateDebut);
            $dateFin = new DateTime($dateFin);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Format de date invalide. Utilisez le format Y-m-d'
            ], 400);
        }

        $stats = $this->statistiquesService->getStatistiquesPeriode($dateDebut, $dateFin);

        return $this->json([
            'success' => true,
            'periode' => [
                'debut' => $dateDebut->format('Y-m-d'),
                'fin' => $dateFin->format('Y-m-d')
            ],
            'data' => [
                'total_commandes' => $stats['total_commandes'],
                'commandes_payees' => $stats['commandes_payees'],
                'commandes_en_attente' => $stats['commandes_en_attente'],
                'commandes_annulees' => $stats['commandes_annulees'],
                'total_recettes' => (float) $stats['total_recettes']
            ]
        ]);
    }

    /**
     * Récupérer les commandes en cours
     */
    #[Route('/commandes-en-cours', name: 'commandes_en_cours', methods: ['GET'])]
    public function commandesEnCours(): JsonResponse
    {
        $commandes = $this->statistiquesService->getCommandesEnCoursJour();

        return $this->json([
            'success' => true,
            'count' => count($commandes),
            'data' => $this->serializeCommandesSimple($commandes)
        ]);
    }

    /**
     * Récupérer les commandes validées
     */
    #[Route('/commandes-validees', name: 'commandes_validees', methods: ['GET'])]
    public function commandesValidees(): JsonResponse
    {
        $commandes = $this->statistiquesService->getCommandesValideeJour();

        return $this->json([
            'success' => true,
            'count' => count($commandes),
            'data' => $this->serializeCommandesSimple($commandes)
        ]);
    }

    /**
     * Récupérer les commandes annulées
     */
    #[Route('/commandes-annulees', name: 'commandes_annulees', methods: ['GET'])]
    public function commandesAnnulees(): JsonResponse
    {
        $commandes = $this->statistiquesService->getCommandesAnnuleesJour();

        return $this->json([
            'success' => true,
            'count' => count($commandes),
            'data' => $this->serializeCommandesSimple($commandes)
        ]);
    }

    /**
     * Récupérer les burgers les plus vendus
     */
    #[Route('/burgers-plus-vendus', name: 'burgers_plus_vendus', methods: ['GET'])]
    public function burgersPlusVendus(): JsonResponse
    {
        $burgers = $this->statistiquesService->getBurgersPlusVendusJour();

        return $this->json([
            'success' => true,
            'count' => count($burgers),
            'data' => array_map(fn($item) => [
                'burger_id' => $item[0]->getId(),
                'burger_nom' => $item[0]->getNom(),
                'burger_prix' => (float) $item[0]->getPrix(),
                'quantite_vendue' => $item['ventes'] ?? 0
            ], $burgers)
        ]);
    }

    /**
     * Récupérer les recettes journalières
     */
    #[Route('/recettes', name: 'recettes', methods: ['GET'])]
    public function recettes(): JsonResponse
    {
        $total = $this->statistiquesService->getRecettesJournalieres();

        return $this->json([
            'success' => true,
            'date' => (new DateTime())->format('Y-m-d'),
            'data' => [
                'total_recettes' => (float) $total,
                'devise' => 'XOF'
            ]
        ]);
    }

    /**
     * Serialize les commandes (simple)
     */
    private function serializeCommandesSimple(array $commandes): array
    {
        return array_map(fn($c) => [
            'id' => $c->getId(),
            'client_nom' => $c->getClient()->getNom() . ' ' . $c->getClient()->getPrenom(),
            'client_telephone' => $c->getClient()->getTelephone(),
            'date' => $c->getDateCommande()->format('Y-m-d H:i:s'),
            'montant' => (float) $c->getMontantTotal(),
            'etat' => $c->getEtat(),
            'payee' => $c->isPayee()
        ], $commandes);
    }
}

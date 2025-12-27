<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Repository\LivraisonRepository;
use App\Service\LivraisonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/livraisons', name: 'api_livraisons_')]
class LivraisonController extends AbstractController
{
    public function __construct(
        private LivraisonService $livraisonService,
        private EntityManagerInterface $em
    ) {}

    /**
     * Récupérer les livraisons en attente
     */
    #[Route('/en-attente', name: 'en_attente', methods: ['GET'])]
    public function enAttente(): JsonResponse
    {
        $livraisons = $this->livraisonService->getLivraisonsEnAttente();

        return $this->json([
            'success' => true,
            'count' => count($livraisons),
            'data' => $this->serializeLivraisons($livraisons)
        ]);
    }

    /**
     * Regrouper les commandes par zone
     */
    #[Route('/regrouper-par-zone', name: 'regrouper_par_zone', methods: ['GET'])]
    public function regrouperParZone(): JsonResponse
    {
        $groupes = $this->livraisonService->regrouperCommandesParZone();

        $data = [];
        foreach ($groupes as $zoneId => $groupe) {
            $data[] = [
                'zone_id' => $groupe['zone']->getId(),
                'zone_nom' => $groupe['zone']->getNom(),
                'prix_livraison' => (float) $groupe['zone']->getPrixLivraison(),
                'nombre_commandes' => count($groupe['commandes']),
                'commandes' => array_map(fn($c) => [
                    'id' => $c->getId(),
                    'client' => $c->getClient()->getNom() . ' ' . $c->getClient()->getPrenom(),
                    'telephone' => $c->getClient()->getTelephone(),
                    'adresse' => $c->getClient()->getAdresse(),
                    'montant' => (float) $c->getMontantTotal()
                ], $groupe['commandes'])
            ];
        }

        return $this->json([
            'success' => true,
            'count' => count($data),
            'data' => $data
        ]);
    }

    /**
     * Assigner un livreur à une livraison
     */
    #[Route('/{id}/assigner-livreur', name: 'assigner_livreur', methods: ['POST'])]
    public function assignerLivreur(int $id, Request $request): JsonResponse
    {
        $livraison = $this->em->getRepository(Livraison::class)->find($id);

        if (!$livraison) {
            return $this->json(['error' => 'Livraison non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $this->livraisonService->assignerLivreur($livraison, $data['livreur_id']);

            return $this->json([
                'success' => true,
                'message' => 'Livreur assigné avec succès',
                'data' => $this->serializeLivraison($livraison)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Marquer une livraison comme complète
     */
    #[Route('/{id}/livrer', name: 'livrer', methods: ['POST'])]
    public function livrer(int $id): JsonResponse
    {
        $livraison = $this->em->getRepository(Livraison::class)->find($id);

        if (!$livraison) {
            return $this->json(['error' => 'Livraison non trouvée'], 404);
        }

        try {
            $this->livraisonService->marquerLivree($livraison);

            return $this->json([
                'success' => true,
                'message' => 'Livraison complétée',
                'data' => $this->serializeLivraison($livraison)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Signaler une livraison échouée
     */
    #[Route('/{id}/echouee', name: 'echouee', methods: ['POST'])]
    public function echouee(int $id, Request $request): JsonResponse
    {
        $livraison = $this->em->getRepository(Livraison::class)->find($id);

        if (!$livraison) {
            return $this->json(['error' => 'Livraison non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $this->livraisonService->marquerEchouee($livraison, $data['raison'] ?? '');

            return $this->json([
                'success' => true,
                'message' => 'Livraison marquée comme échouée',
                'data' => $this->serializeLivraison($livraison)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Récupérer les livraisons d'un livreur
     */
    #[Route('/livreur/{livreurId}', name: 'by_livreur', methods: ['GET'])]
    public function byLivreur(int $livreurId): JsonResponse
    {
        $livraisons = $this->livraisonService->getLivraisonsLivreur($livreurId);

        return $this->json([
            'success' => true,
            'count' => count($livraisons),
            'data' => $this->serializeLivraisons($livraisons)
        ]);
    }

    /**
     * Serialize une livraison
     */
    private function serializeLivraison(Livraison $livraison): array
    {
        $livreur = $livraison->getLivreur();

        return [
            'id' => $livraison->getId(),
            'commande' => [
                'id' => $livraison->getCommande()->getId(),
                'montant' => (float) $livraison->getCommande()->getMontant(),
                'client' => $livraison->getCommande()->getClient()->getNom() . ' ' . 
                           $livraison->getCommande()->getClient()->getPrenom()
            ],
            'zone' => [
                'id' => $livraison->getZone()->getId(),
                'nom' => $livraison->getZone()->getNom(),
                'prix' => (float) $livraison->getZone()->getPrixLivraison()
            ],
            'livreur' => $livreur ? [
                'id' => $livreur->getId(),
                'nom' => $livreur->getNom(),
                'prenom' => $livreur->getPrenom(),
                'telephone' => $livreur->getTelephone()
            ] : null,
            'etat' => $livraison->getEtat(),
            'date_assignation' => $livraison->getDateAssignation()?->format('Y-m-d H:i:s'),
            'date_livraison' => $livraison->getDateLivraison()?->format('Y-m-d H:i:s'),
            'notes' => $livraison->getNotes()
        ];
    }

    /**
     * Serialize plusieurs livraisons
     */
    private function serializeLivraisons(array $livraisons): array
    {
        return array_map(fn($l) => $this->serializeLivraison($l), $livraisons);
    }
}

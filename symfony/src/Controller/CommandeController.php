<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/commandes', name: 'api_commandes_')]
class CommandeController extends AbstractController
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private CommandeService $commandeService,
        private EntityManagerInterface $em
    ) {}

    /**
     * Lister toutes les commandes
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $etat = $request->query->get('etat');
        $clientId = $request->query->get('clientId');
        $page = (int) ($request->query->get('page') ?? 1);
        $limit = (int) ($request->query->get('limit') ?? 20);

        $query = $this->em->createQueryBuilder()
            ->select('c')
            ->from(Commande::class, 'c')
            ->orderBy('c.dateCommande', 'DESC');

        if ($etat) {
            $query->andWhere('c.etat = :etat')->setParameter('etat', $etat);
        }

        if ($clientId) {
            $query->andWhere('c.client = :clientId')->setParameter('clientId', $clientId);
        }

        $total = (int) (clone $query)->select('COUNT(c.id)')->getQuery()->getSingleScalarResult();

        $commandes = $query
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

        return $this->json([
            'success' => true,
            'data' => $this->serializeCommandes($commandes),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    /**
     * Récupérer une commande
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $commande = $this->commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $this->serializeCommande($commande)
        ]);
    }

    /**
     * Créer une nouvelle commande
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $commande = $this->commandeService->creerCommande(
                $data['clientId'] ?? null,
                $data['type'] ?? Commande::TYPE_PLACE
            );

            // Ajouter les burgers
            if (isset($data['burgers'])) {
                foreach ($data['burgers'] as $burger) {
                    $this->commandeService->ajouterBurger(
                        $commande,
                        $burger['id'],
                        $burger['quantite'] ?? 1
                    );
                }
            }

            // Ajouter les menus
            if (isset($data['menus'])) {
                foreach ($data['menus'] as $menu) {
                    $this->commandeService->ajouterMenu(
                        $commande,
                        $menu['id'],
                        $menu['quantite'] ?? 1
                    );
                }
            }

            // Ajouter les compléments
            if (isset($data['complements'])) {
                foreach ($data['complements'] as $complement) {
                    $this->commandeService->ajouterComplement(
                        $commande,
                        $complement['id'],
                        $complement['quantite'] ?? 1
                    );
                }
            }

            $this->em->persist($commande);
            $this->em->flush();

            return $this->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'data' => $this->serializeCommande($commande)
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Confirmer une commande
     */
    #[Route('/{id}/confirmer', name: 'confirmer', methods: ['POST'])]
    public function confirmer(int $id): JsonResponse
    {
        $commande = $this->commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        try {
            $this->commandeService->confirmerCommande($commande);

            return $this->json([
                'success' => true,
                'message' => 'Commande confirmée',
                'data' => $this->serializeCommande($commande)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Terminer une commande
     */
    #[Route('/{id}/terminer', name: 'terminer', methods: ['POST'])]
    public function terminer(int $id): JsonResponse
    {
        $commande = $this->commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        try {
            $this->commandeService->terminerCommande($commande);

            return $this->json([
                'success' => true,
                'message' => 'Commande terminée',
                'data' => $this->serializeCommande($commande)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Annuler une commande
     */
    #[Route('/{id}/annuler', name: 'annuler', methods: ['POST'])]
    public function annuler(int $id, Request $request): JsonResponse
    {
        $commande = $this->commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        try {
            $this->commandeService->annulerCommande($commande);

            return $this->json([
                'success' => true,
                'message' => 'Commande annulée',
                'data' => $this->serializeCommande($commande)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Enregistrer un paiement
     */
    #[Route('/{id}/paiement', name: 'paiement', methods: ['POST'])]
    public function paiement(int $id, Request $request): JsonResponse
    {
        $commande = $this->commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $paiement = $this->commandeService->effectuerPaiement(
                $commande,
                $data['montant'] ?? $commande->getMontant(),
                $data['methode'] ?? 'ESPECE'
            );

            return $this->json([
                'success' => true,
                'message' => 'Paiement enregistré',
                'data' => [
                    'paiement' => [
                        'id' => $paiement->getId(),
                        'montant' => $paiement->getMontant(),
                        'methode' => $paiement->getMethode(),
                        'date' => $paiement->getDatePaiement()->format('Y-m-d H:i:s')
                    ],
                    'commande' => $this->serializeCommande($commande)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Serialize une commande pour la réponse JSON
     */
    private function serializeCommande(Commande $commande): array
    {
        return [
            'id' => $commande->getId(),
            'client' => [
                'id' => $commande->getClient()->getId(),
                'nom' => $commande->getClient()->getNom(),
                'prenom' => $commande->getClient()->getPrenom(),
                'telephone' => $commande->getClient()->getTelephone(),
                'quartier' => $commande->getClient()->getQuartier()
            ],
            'dateCommande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
            'etat' => $commande->getEtat(),
            'mode' => $commande->getType(),
            'montantTotal' => (float) $commande->getMontant(),
            'payee' => $commande->isPayee(),
            'notes' => $commande->getNotes(),
            'burgers' => array_map(fn($cb) => [
                'id' => $cb->getBurger()->getId(),
                'nom' => $cb->getBurger()->getNom(),
                'quantite' => $cb->getQuantite(),
                'prix' => (float) $cb->getPrixUnitaire()
            ], $commande->getBurgers()->toArray()),
            'menus' => array_map(fn($cm) => [
                'id' => $cm->getMenu()->getId(),
                'nom' => $cm->getMenu()->getNom(),
                'quantite' => $cm->getQuantite(),
                'prix' => (float) $cm->getPrixUnitaire()
            ], $commande->getMenus()->toArray()),
            'complements' => array_map(fn($cc) => [
                'id' => $cc->getComplement()->getId(),
                'nom' => $cc->getComplement()->getNom(),
                'quantite' => $cc->getQuantite(),
                'prix' => (float) $cc->getPrixUnitaire()
            ], $commande->getComplements()->toArray()),
            'paiement' => $commande->getPaiement() ? [
                'montant' => (float) $commande->getPaiement()->getMontant(),
                'methode' => $commande->getPaiement()->getMethode(),
                'date' => $commande->getPaiement()->getDatePaiement()->format('Y-m-d H:i:s')
            ] : null
        ];
    }

    /**
     * Serialize plusieurs commandes
     */
    private function serializeCommandes(array $commandes): array
    {
        return array_map(fn($c) => $this->serializeCommande($c), $commandes);
    }
}

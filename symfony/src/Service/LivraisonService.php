<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Zone;
use App\Repository\CommandeRepository;
use App\Repository\LivreurRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;

class LivraisonService
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private LivreurRepository $livreurRepository,
        private ZoneRepository $zoneRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Créer une livraison pour une commande
     */
    public function creerLivraison(Commande $commande, int $zoneId): Livraison
    {
        $zone = $this->zoneRepository->find($zoneId);
        if (!$zone) {
            throw new \InvalidArgumentException('Zone non trouvée');
        }

        // Vérifier que la commande est en mode livraison
        if ($commande->getType() !== Commande::TYPE_LIVRAISON) {
            throw new \InvalidArgumentException('Cette commande n\'est pas en mode livraison');
        }

        $livraison = new Livraison();
        $livraison->setCommande($commande);
        $livraison->setZone($zone);
        $livraison->setEtat(Livraison::STATUS_ASSIGNEE);
        $livraison->setDateAssignation(new \DateTime());

        $commande->setLivraison($livraison);

        $this->em->persist($livraison);
        $this->em->persist($commande);
        $this->em->flush();

        return $livraison;
    }

    /**
     * Assigner un livreur à une livraison
     */
    public function assignerLivreur(Livraison $livraison, int $livreurId): void
    {
        $livreur = $this->livreurRepository->find($livreurId);
        if (!$livreur) {
            throw new \InvalidArgumentException('Livreur non trouvé');
        }

        if (!$livreur->isActif()) {
            throw new \InvalidArgumentException('Ce livreur n\'est pas actif');
        }

        $livraison->setLivreur($livreur);
        $livraison->setEtat(Livraison::STATUS_EN_ROUTE);

        $this->em->persist($livraison);
        $this->em->flush();
    }

    /**
     * Marquer une livraison comme complète
     */
    public function marquerLivree(Livraison $livraison): void
    {
        $livraison->setEtat(Livraison::STATUS_LIVREE);
        $livraison->setDateLivraison(new \DateTime());

        $commande = $livraison->getCommande();
        $commande->setEtat(Commande::STATUS_TERMINER);

        $this->em->persist($livraison);
        $this->em->persist($commande);
        $this->em->flush();
    }

    /**
     * Signaler une livraison échouée
     */
    public function marquerEchouee(Livraison $livraison, string $raison = ''): void
    {
        $livraison->setEtat(Livraison::STATUS_ECHOUEE);
        $livraison->setNotes($raison);

        $this->em->persist($livraison);
        $this->em->flush();
    }

    /**
     * Regrouper les commandes par zone pour livraison
     */
    public function regrouperCommandesParZone(): array
    {
        $commandes = $this->commandeRepository->findBy([
            'type' => Commande::TYPE_LIVRAISON,
            'etat' => Commande::STATUS_TERMINER,
            'payee' => true
        ]);

        $groupes = [];
        foreach ($commandes as $commande) {
            // Récupérer la zone basée sur le quartier du client
            $client = $commande->getClient();
            $zones = $this->zoneRepository->findAllOrderedByName();

            $zoneAssignee = null;
            foreach ($zones as $zone) {
                $quartiers = $zone->getQuartiers();
                foreach ($quartiers as $quartier) {
                    if ($quartier->getNom() === $client->getQuartier()) {
                        $zoneAssignee = $zone;
                        break 2;
                    }
                }
            }

            if ($zoneAssignee) {
                if (!isset($groupes[$zoneAssignee->getId()])) {
                    $groupes[$zoneAssignee->getId()] = [
                        'zone' => $zoneAssignee,
                        'commandes' => []
                    ];
                }
                $groupes[$zoneAssignee->getId()]['commandes'][] = $commande;
            }
        }

        return $groupes;
    }

    /**
     * Récupérer les livraisons en attente d'assignation
     */
    public function getLivraisonsEnAttente(): array
    {
        return $this->em->getRepository(Livraison::class)->findBy([
            'etat' => Livraison::STATUS_ASSIGNEE
        ]);
    }

    /**
     * Récupérer les livraisons assignées à un livreur
     */
    public function getLivraisonsLivreur(int $livreurId): array
    {
        return $this->em->createQueryBuilder()
            ->select('l')
            ->from(Livraison::class, 'l')
            ->where('l.livreur = :livreur')
            ->andWhere('l.etat IN (:etats)')
            ->setParameter('livreur', $livreurId)
            ->setParameter('etats', [Livraison::STATUS_EN_ROUTE, Livraison::STATUS_ASSIGNEE])
            ->getQuery()
            ->getResult()
        ;
    }
}
